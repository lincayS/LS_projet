<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Form\JeansType;
use App\Repository\JeansRepository;
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
     * @Route("/{id}", name="catalog_show", methods={"GET"})
     */
    public function show(Jeans $jeans): Response
    {
        return $this->render('catalog/show.html.twig', [
            'jeans' => $jeans,
        ]);
    }

   

    
}
