<?php

namespace App\Controller;

use App\Entity\CarteFidelite;
use App\Form\CarteFideliteType;
use App\Repository\CarteFideliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/carte_fidelite")
 */
class CarteFideliteController extends AbstractController
{
    /**
     * @Route("/", name="carte_fidelite_index", methods={"GET"})
     */
    public function index(CarteFideliteRepository $carteFideliteRepository): Response
    {
        return $this->render('carte_fidelite/index.html.twig', [
            'carte_fidelites' => $carteFideliteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="carte_fidelite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $carteFidelite = new CarteFidelite();
        $form = $this->createForm(CarteFideliteType::class, $carteFidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($carteFidelite);
            $entityManager->flush();

            return $this->redirectToRoute('carte_fidelite_index');
        }

        return $this->render('carte_fidelite/new.html.twig', [
            'carte_fidelite' => $carteFidelite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="carte_fidelite_show", methods={"GET"})
     */
    public function show(CarteFidelite $carteFidelite): Response
    {
        return $this->render('carte_fidelite/show.html.twig', [
            'carte_fidelite' => $carteFidelite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="carte_fidelite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CarteFidelite $carteFidelite): Response
    {
        $form = $this->createForm(CarteFideliteType::class, $carteFidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('carte_fidelite_index');
        }

        return $this->render('carte_fidelite/edit.html.twig', [
            'carte_fidelite' => $carteFidelite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="carte_fidelite_delete", methods={"POST"})
     */
    public function delete(Request $request, CarteFidelite $carteFidelite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carteFidelite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($carteFidelite);
            $entityManager->flush();
        }

        return $this->redirectToRoute
        ('carte_fidelite_index', [], Response::HTTP_SEE_OTHER);
    }
    }