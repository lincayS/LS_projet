<?php

namespace App\Controller;

use App\Entity\DetailCommande;
use App\Form\DetailCommandeType;
use App\Repository\DetailCommandeRepository;
use App\Repository\PurchaseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/detail/commande")
 */
class AdminDetailCommandeController extends AbstractController
{
    /**
     * @Route("/", name="admin_detail_commande_index", methods={"GET"})
     */
    public function index(DetailCommandeRepository $detailCommandeRepository): Response
    {
        //on affiche les objets DetailCommandeRepository
        return $this->render('admin_detail_commande/index.html.twig', [
            'detail_commandes' => $detailCommandeRepository->findAll(),


        ]);
    }

    /**
     * @Route("/new", name="admin_detail_commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

          // on instancie un nouvel espace avec la structure de l'entité detailCommande vide
        $detailCommande = new DetailCommande();         
        // on creer un formulaire a partir de detailCommandeType
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        //on recupere les données inscrites
        $form->handleRequest($request);

        // si le formulair est soumis et est valide alors...
        if ($form->isSubmitted() && $form->isValid()) {

             //On demande à sauvegarder jeans en bdd
            $entityManager->persist($detailCommande);             
            //on valide la demande
            $entityManager->flush();

            //on ridirige la page
            return $this->redirectToRoute('admin_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }
        // on affiche le detailCommande et le formulaire
        return $this->renderForm('admin_detail_commande/new.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_detail_commande_show", methods={"GET"})
     */
    public function show(DetailCommande $detailCommande): Response
    {
        //on affiche le detailCommande
        return $this->render('admin_detail_commande/show.html.twig', [
            'detail_commande' => $detailCommande,


        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_detail_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DetailCommande $detailCommande, EntityManagerInterface $entityManager): Response
    {
 
         // on creer un formulaire a partir de detailCommandeType
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        //on recupere les données inscrites
        $form->handleRequest($request);
         // si le formulaire est soumis et est valide alors...         
        if ($form->isSubmitted() && $form->isValid()) {
            //on valide toutes les modifications apportées
            $entityManager->flush();

            //on redirige la page
            return $this->redirectToRoute('admin_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        //on affiche le detailCommande et le formulaire
        return $this->renderForm('admin_detail_commande/edit.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_detail_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, DetailCommande $detailCommande, EntityManagerInterface $entityManager): Response
    {
        //on verifie que le formulaire soit bien celui que l'on à envoyé
        if ($this->isCsrfTokenValid('delete'.$detailCommande->getId(), $request->request->get('_token'))) {
            //on supprime le produit 
            $entityManager->remove($detailCommande);
            //on applique la suppression en bdd
            $entityManager->flush();
        }
        //on redirige
        return $this->redirectToRoute('admin_detail_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}

