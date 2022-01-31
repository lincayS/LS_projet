<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PconfidentialiteController extends AbstractController
{
    /**
     * @Route("/pconfidentialite", name="pconfidentialite")
     */
    public function index(): Response
    {
        return $this->render('pconfidentialite/index.html.twig', [
            'controller_name' => 'PconfidentialiteController',
        ]);
    }
}
