<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, EmailService $emailService): Response
    {

        //on créer un formulaire à partir de contactype
        $formulaire = $this->createForm(ContactType::class);
        //on récupère les données soumises
        $formulaire->handleRequest($request);

        //si le formulaire est envoyeé, alors... 
        if($formulaire->isSubmitted()){

            //on récupère les données
            $data = $formulaire->getData();
            //on récupère chacun des champs individuellement
            $mail = $data['email'];
            $nom = $data['nom'];
            $prenom = $data['prenom'];

            $admin = 'admin@site.com';
            $objet = $data ['objet'];
            $message = $data ['message']; 
            // on passe des champs a l'emaiservice pour les afficher dans la vue
            $tableT = ['emailfrom' => $admin, 'to'=> $mail];
            $table = ['texte'=> $message];
            
            //on appelle la fonction 'envoyer' de l'emailservice avec ses paramètres
            $emailService->envoyer($mail,$admin,$objet,'emails/accuse.html.twig', $table);

            

            $emailService->envoyer($admin,$mail,$objet,'emails/contact.html.twig',$tableT );


            //on transmet les données à la vue pour affichage
            return $this->render('contact/success.html.twig',[

                'email' => $mail,
                'nom' => $nom,
                'prenom' => $prenom,

               

            ]);
            //sinon,
        } else {
            //on transmet le formulaire
        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formAfficher' => $formulaire,

        ]);
    }
        
    }
}
