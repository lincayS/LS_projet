<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
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

            

            //on affiche les données
            return $this->render('contact/success.html.twig',[

                'email' => $mail,
                'nom' => $nom,
                'prenom' => $prenom,

               

            ]);
            //sinon,
        } else {
            //on affiche le formulaire
        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formAfficher' => $formulaire,

        ]);
    }
        
    }
}
