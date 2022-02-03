<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Form\ColorJeansType;
use App\Repository\JeansRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalog")
 */
class CatalogController extends AbstractController
{
    /**
     * @Route("/", name="catalog_index", methods={"GET"})
     */
    public function index(Request $request, JeansRepository $jeansRepository): Response
    {
        //on récupère la valeur de search dans l'url
        $search = $request->query->get('search');
        // si cette valeur existe
        if($search){
            //on appelle la fonction dans le repository
            $commande = $jeansRepository->findBySearch($search);
        } else {
            //sinon, on recupère tous les objets jeans
            $commande = $jeansRepository->findAll();
        }
        //on affiche les objets Jeans
        return $this->render('catalog/index.html.twig', [
            'jeans' => $commande


        ]);
    }


    /**
     * @Route("/{id}", name="catalog_show", methods={"GET", "POST"})
     */
    public function show(Jeans $jeans, Request $request, CartService $cartService): Response
    {   
        //on créer un formulaire à partir de colorjeanstype
        $formulaire = $this->createForm(ColorJeansType::class);
        //on recupère les donées entrées
        $formulaire->handleRequest($request);

        //si le formulaire est soumis et valide...
        if ($formulaire->isSubmitted() && $formulaire->isValid())
         {
            //on récupère les données
            $data = $formulaire->getData();
            //on récupère le champs couleur
            $couleur = $data['couleur'];
            
            //on ajoute l'objet jeans et la couleur au panier
            $cartService->add($jeans, $couleur);
        
        //on redirige la page
       return $this->redirectToRoute('cart_index');

        }
        //On affiche jeans et le formulaire
        return $this->renderForm('catalog/show.html.twig', [
            'controller_name' => 'CatalogController',
            'formAfficher' => $formulaire,
            'jeans' => $jeans,
        ]);

    }

   

    
}
