<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Security;


class RegistrationController extends AbstractController
{

    private $verifyEmailHelper;
    private $mailer;
    private $security;
    
    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, Security $security)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->security= $security;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // on instancie un nouvel espace avec la structure de l'entité user vide
        $user = new User();

        //on créer un formulaire à partie de RegistrationFormType
        $form = $this->createForm(RegistrationFormType::class, $user);
        //on récupère les données envoyées
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on encode le mot de passe brut
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //on demende d'enregistrer user en bdd
            $entityManager->persist($user);

            
            //on valide la demande
            $entityManager->flush();
            // do anything else you need here, like send an email
$signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()] // add the user's id as an extra query param
                                 );
        
            $email = new TemplatedEmail();
            $email->from('admin@ls-couture.com');
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
            
            $this->mailer->send($email);
            //on redirige
            return $this->redirectToRoute('home');
        }

        //on affiche le formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

     /**
     * @Route("/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('profile_show');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('profile_show');
        }

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true

        $user->setRoles(array('ROLE_CLIENT'));

        //on demende d'enregistrer user en bdd
        $entityManager->persist($user);

            
        //on valide la demande
        $entityManager->flush();
        $this->addFlash('success', 'Your e-mail address has been verified.');

        return $this->redirectToRoute('profile_show');
    }

     /**
     * @Route("/resend", name="registration_resend")
     */
    public function resend(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
/** @var User $user */
$user = $this->security->getUser();
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()] // add the user's id as an extra query param
                             );
    
        $email = new TemplatedEmail();
        $email->from('admin@ls-couture.com');
        $email->to($user->getEmail());
        $email->htmlTemplate('registration/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
        
        $this->mailer->send($email);
        return $this->redirectToRoute('home');

    }
}



