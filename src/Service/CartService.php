<?php
namespace App\Service;

use App\Entity\Jeans;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class CartService
{

    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    public function get()
    {
        return $this->sessionInterface->get('cart', [
            'elements' => [],
            'total' => 0.0
        ]);
    }

    public function add(Jeans $jeans, ?string $couleur)
    {
        $cart = $this->get();
        $jeansId = $jeans->getId().$couleur;

        if (!isset($cart['elements'][$jeansId]))
        {
            $cart['elements'][$jeansId] = [
                'jeans' => $jeans,
                'quantity' => 0,
                'couleur' => $couleur,
            ];
        }

        $cart['total'] = $cart['total'] + $jeans->getPrice();
        $cart['elements'][$jeansId]['quantity'] = $cart['elements'][$jeansId]['quantity'] + 1;

        $this->sessionInterface->set('cart', $cart);
    }

  
    public function clear()
    {
        $this->sessionInterface->remove('cart');
    }
}