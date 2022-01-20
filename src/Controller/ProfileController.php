<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    
    /**
     * @Route("/", name="profile_show", methods={"GET"})
     */
    public function show(): Response
    {   //on recupère l'objet dans le User
        $user = $this->getUser();
        //on affiche l'objet
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit", name="profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
         //on recupère l'objet dans le User
        $user = $this->getUser();
        //on créer un formulaire a partir de User1Type
        $form = $this->createForm(User1Type::class, $user);
        //on récupère les données du $form
        $form->handleRequest($request);
        //si le formulaire est soumis et valide...
        if ($form->isSubmitted() && $form->isValid()) {
            //on execute tout les changements effectués
            $entityManager->flush();
            //on redirige la page
            return $this->redirectToRoute('profile_show', [], Response::HTTP_SEE_OTHER);
        }
        //affiche le user et le formulaire
        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

  
}
