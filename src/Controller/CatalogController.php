<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Form\ColorJeansType;
use App\Form\JeansType;
use App\Repository\JeansRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(JeansRepository $jeansRepository): Response
    {
        return $this->render('catalog/index.html.twig', [
            'jeans' => $jeansRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="catalog_show", methods={"GET", "POST"})
     */
    public function show(Jeans $jeans, Request $request, CartService $cartService): Response
    {
        $formulaire = $this->createForm(ColorJeansType::class);

        $formulaire->handleRequest($request);


        if ($formulaire->isSubmitted() && $formulaire->isValid())
         {

            $data = $formulaire->getData();
            $couleur = $data['couleur'];
            
            $cartService->add($jeans, $couleur);

       return $this->redirectToRoute('cart_index');

        }
        return $this->renderForm('catalog/show.html.twig', [
            'controller_name' => 'CatalogController',
            'formAfficher' => $formulaire,
            'jeans' => $jeans,
        ]);

    }

   

    
}
