<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MlegalesController extends AbstractController
{
    /**
     * @Route("/mlegales", name="mlegales")
     */
    public function index(): Response
    {
        return $this->render('mlegales/index.html.twig', [
            'controller_name' => 'MlegalesController',
        ]);
    }
}
