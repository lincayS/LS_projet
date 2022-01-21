<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use App\Service\CartService;
use DateTime;
use App\Service\PaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
   

     /**
     * @Route("/payment", name="payment_index")
     */
    public function index(PaymentService $paymentService): Response
    {

        $sessionId = $paymentService->create();

        $paymentRequest =new Paiement();
        $paymentRequest->setCreatedAt(new DateTime());
        $paymentRequest->setStripeSessionId($sessionId);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($paymentRequest);
        $entityManager->flush();
        
        return $this->render('payment/index.html.twig', [
            'sessionId' => $sessionId,
        ]);
    }  
    
    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    public function success(string $stripeSessionId, PaiementRepository $paiementRepository, CartService $cartService): Response
    {
        $paymentRequest = $paiementRepository->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);
        if (!$paymentRequest)
        {
            return $this->redirectToRoute('cart_index');
        }

        $paymentRequest->setValidated(true);
        $paymentRequest->setPaidAt(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $cartService->clear();

        return $this->render('payment/success.html.twig');
    }

    /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    public function failure(string $stripeSessionId, PaiementRepository $paiementRepository): Response
    {

        $paymentRequest = $paiementRepository->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);
        if (!$paymentRequest)
        {
            return $this->redirectToRoute('cart_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($paymentRequest);
        $entityManager->flush();

        return $this->render('payment/failure.html.twig');
    }
}


