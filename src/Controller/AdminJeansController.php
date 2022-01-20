<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Form\Jeans1Type;
use App\Repository\JeansRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/jeans")
 */
class AdminJeansController extends AbstractController
{
    /**
     * @Route("/", name="admin_jeans_index", methods={"GET"})
     */
    public function index(JeansRepository $jeansRepository): Response
    {       //on affiche tout les jeans de la bdd
        return $this->render('admin_jeans/index.html.twig', [
            'jeans' => $jeansRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_jeans_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {   // on instancie un nouvel espace avec la structur de l'entité jeans vide
        $jeans = new Jeans();
        // on creer un formulaire a partir de Jeans1Type
        $form = $this->createForm(Jeans1Type::class, $jeans);
        //on recupere les données inscrites
        $form->handleRequest($request);

        // si le formulair est soumis et est valide alors...
        if ($form->isSubmitted() && $form->isValid()) {
           
            //on récupère l'image du formulaire
            $imageFile = $form->get('picture')->getData();
            //si il y a une image, alors...
            if ($imageFile) {
                //on teélécharge l'image avec la méthode du service vers le dossier uploads
                $imageFileName = $fileUploader->upload($imageFile);
                //on met le nom de l'image en bdd
                $jeans->setPicture($imageFileName);
            }
           //On demande à sauvegarder jeans en bdd
            $entityManager->persist($jeans);
            //on valide la demande
            $entityManager->flush();

            //on redirige vers l'index de jeans admin
            return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche le formulaire et les jeans
        return $this->renderForm('admin_jeans/new.html.twig', [
            'jeans' => $jeans,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_jeans_show", methods={"GET"})
     */
    public function show(Jeans $jeans): Response
    {   //on affiche un jeans
        return $this->render('admin_jeans/show.html.twig', [
            'jeans' => $jeans,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_jeans_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Jeans $jeans, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {   
        //on créer un formulaire à partir de jeans1type
        $form = $this->createForm(Jeans1Type::class, $jeans);
        //on recupere les données saisies 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $imageFile = $form->get('picture')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $jeans->setPicture($imageFileName);
            }
           
            //on upload toutes les modification apportées au jeans
            $entityManager->flush();

            return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_jeans/edit.html.twig', [
            'jeans' => $jeans,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_jeans_delete", methods={"POST"})
     */
    public function delete(Request $request, Jeans $jeans, EntityManagerInterface $entityManager): Response
    {   
        //on verifie que le formulaire soit bien celui que l'on à envoyé
        if ($this->isCsrfTokenValid('delete'.$jeans->getId(), $request->request->get('_token'))) {
            //on supprime le produit 
            $entityManager->remove($jeans);
            //on applique la suppression en bdd
            $entityManager->flush();
        }
        //on redirige
        return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
    }
}
