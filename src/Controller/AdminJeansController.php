<?php

namespace App\Controller;

use App\Entity\Jeans;
use App\Form\Jeans1Type;
use App\Repository\JeansRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/jeans")
 */
class AdminJeansController extends AbstractController
{
    /**
     * @Route("/", name="admin_jeans_index", methods={"GET"})
     */
    public function index(JeansRepository $jeansRepository): Response
    {
        return $this->render('admin_jeans/index.html.twig', [
            'jeans' => $jeansRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_jeans_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $jeans = new Jeans();
        $form = $this->createForm(Jeans1Type::class, $jeans);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $imageFile = $form->get('picture')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $jeans->setPicture($imageFileName);
            }
           
            $entityManager->persist($jeans);
            $entityManager->flush();

            return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_jeans/new.html.twig', [
            'jeans' => $jeans,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_jeans_show", methods={"GET"})
     */
    public function show(Jeans $jeans): Response
    {
        return $this->render('admin_jeans/show.html.twig', [
            'jeans' => $jeans,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_jeans_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Jeans $jeans, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(Jeans1Type::class, $jeans);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $imageFile = $form->get('picture')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $jeans->setPicture($imageFileName);
            }
           

            $entityManager->flush();

            return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_jeans/edit.html.twig', [
            'jeans' => $jeans,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_jeans_delete", methods={"POST"})
     */
    public function delete(Request $request, Jeans $jeans, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeans->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jeans);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_jeans_index', [], Response::HTTP_SEE_OTHER);
    }
}
