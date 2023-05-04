<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Repository\SuiviReclamationRepository;
use App\Entity\SuiviReclamation;
use App\Form\SuiviReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi/reclamation')]
class SuiviReclamationController extends AbstractController
{
    #[Route('/', name: 'app_suivi_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $suiviReclamations = $entityManager
            ->getRepository(SuiviReclamation::class)
            ->findAll();

        return $this->render('suivi_reclamation/index.html.twig', [
            'suivi_reclamations' => $suiviReclamations,
        ]);
    }
    #[Route('/frontsuivi', name: 'app_suivi_reclamation_index_front', methods: ['GET'])]
    public function indexfront(EntityManagerInterface $entityManager): Response
    {
        $suiviReclamations = $entityManager
            ->getRepository(SuiviReclamation::class)
            ->findAll();

        return $this->render('suivi_reclamation/indexfrontsuiv.html.twig', [
            'suivi_reclamations' => $suiviReclamations,
        ]);
    }
   # src/Controller/ReclamationController.php




#[Route('/suivistat', name: 'app_suivireclamation_stat', methods: ['GET'])]
public function statistics(SuiviReclamationRepository $repo): Response
{
    $suivi = $repo->countAll();

    dump($suivi); // Dump the value of $suivi to check if it contains the expected value

    return $this->render('reclamation/stats.html.twig', [
        'suivi' => $suivi,
    ]);
}

    
    #[Route('/new', name: 'app_suivi_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviReclamation = new SuiviReclamation();
        $form = $this->createForm(SuiviReclamationType::class, $suiviReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviReclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_reclamation/new.html.twig', [
            'suivi_reclamation' => $suiviReclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_reclamation_show', methods: ['GET'])]
    public function show(SuiviReclamation $suiviReclamation): Response
    {
        return $this->render('suivi_reclamation/show.html.twig', [
            'suivi_reclamation' => $suiviReclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviReclamation $suiviReclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviReclamationType::class, $suiviReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suivi_reclamation/edit.html.twig', [
            'suivi_reclamation' => $suiviReclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviReclamation $suiviReclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviReclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suiviReclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/new/{id}', name: 'app_suivi_reclamation_new_answer')]
    public function newAnswer(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
        $reclamation->setEtat(1);
        $entityManager->persist($reclamation);
            $entityManager->flush();
    
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
    
        $suiviReclamation = new SuiviReclamation();
        $suiviReclamation->setIdReclamation($reclamation);
    
        $form = $this->createForm(SuiviReclamationType::class, $suiviReclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $entityManager->persist($suiviReclamation);
            $entityManager->flush();
            $reclamation->setEtat(2);
        $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_suivi_reclamation_index');
        }
    
        return $this->renderForm('suivi_reclamation/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/suivi-reclamation/chat', name: 'app_suivi_reclamation_chat', methods: ['GET', 'POST'])]
    public function showChat(SuiviReclamationRepository $repository): Response
    {
        $suiviReclamations = $repository->findAll();
    
        return $this->render('suivi_reclamation/chat.html.twig', [
            'suiviReclamations' => $suiviReclamations,
        ]);
    }
    
    

}    