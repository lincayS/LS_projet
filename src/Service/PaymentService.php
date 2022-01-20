<?php
namespace App\Service;

use \Stripe\StripeClient;

class PaymentService
{

    private $cartService;
    private $stripe;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->stripe = new StripeClient('sk_test_51KJfrlJLSprW6bh6oeXfVrE9BLkX74kVeJN5QzjEzY6pMf2WbOsWJ4ScJ5NF0c1uniEzqXwrTPkF3N6KVZsSErAQ001Glbi78g');
    }

    public function create(): string
    {
        $cart = $this->cartService->get();
        $items = [];
        foreach($cart['elements'] as $jeansId => $element)
        {
            $items[] =[
                'amount' => $element['jeans']->getPrice() * 100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['jeans']->getName()
            ];
        }
        $protocol = $_SERVER['HTTPS'] ? 'https' : 'http';
        $host = $_SERVER['SERVER_NAME'];
        $successUrl = $protocol . '://' . $host .'/payment/success/{CHECKOUT_SESSION_ID}';
        $failureUrl = $protocol . '://' . $host . '/payment/failure/{CHECKOUT_SESSION_ID}';

        $session = $this->stripe->checkout->sessions->create([
            'success_url' => $successUrl,
            'cancel_url' => $failureUrl,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $items
        ]);

        return $session->id;
    }
}