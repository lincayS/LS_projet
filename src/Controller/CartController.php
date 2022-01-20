<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(CartService $cartService): Response
    {   //on recupere le panier
        $cart = $cartService->get();
        //on affiche le panier
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart
        ]);
    }

     /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(CartService $cartService, Jeans $jeans): Response
    {  //on ajoute une entité jeans selon l'id plus une couleur null par défault
        $cartService->add($jeans, null);
        // on redirige vers l'index du panier
       return $this->redirectToRoute('cart_index');
    }

    

     /**
     * @Route("/cart/clear", name="cart_clear")
     */
    public function clear(CartService $cartService): Response
    {   // on vide le panier et on redirige
        $cartService->clear();
       return $this->redirectToRoute('cart_index');
    }
}

