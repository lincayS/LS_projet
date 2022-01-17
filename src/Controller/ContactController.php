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


        $formulaire = $this->createForm(ContactType::class);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted()){

            $data = $formulaire->getData();
            $mail = $data['email'];
            $nom = $data['nom'];
            $prenom = $data['prenom'];

            


            return $this->render('contact/success.html.twig',[

                'email' => $mail,
                'nom' => $nom,
                'prenom' => $prenom,

               

            ]);

        } else {
        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formAfficher' => $formulaire,

        ]);
    }
        
    }
}
