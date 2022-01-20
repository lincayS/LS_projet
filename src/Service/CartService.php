<?php
namespace App\Service;

use App\Entity\Jeans;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class CartService
{

    private $sessionInterface;
    
    // fonction magique pour ajouter des propriétés. Le paramètre permet d'agir sur la globale session.
    public function __construct(SessionInterface $sessionInterface)
    {
        // on recupere le composant SessionInterface
        $this->sessionInterface = $sessionInterface;
    }

    public function get()
    {
        // on met les valeurs du panier à 0 par défault
        return $this->sessionInterface->get('cart', [
            'elements' => [],
            'total' => 0.0
        ]);
    }

    public function add(Jeans $jeans, ?string $couleur)
    {
        //on recupere le panier de la méthode précédente
        $cart = $this->get();
        // on recupere l'id de l'entité jeans en bdd plus la couleur selectionnée
        $jeansId = $jeans->getId().$couleur;

        //si on a un id et une couleur, alors...
        if (!isset($cart['elements'][$jeansId]))
        {   
            //on transmet le panier a la vue
            $cart['elements'][$jeansId] = [
                'jeans' => $jeans,
                'quantity' => 0,
                'couleur' => $couleur,
            ];
        }
        //on met a jour le prix total
        $cart['total'] = $cart['total'] + $jeans->getPrice();
        
        //on met a jour la quantité
        $cart['elements'][$jeansId]['quantity'] = $cart['elements'][$jeansId]['quantity'] + 1;
        //on créer un $_SESSION['cart']
        $this->sessionInterface->set('cart', $cart);
    }

   
    public function clear()
    {   //On appelle la methode du cartService pour supprimer la session cart et vider le panier
        $this->sessionInterface->remove('cart');
    }
}