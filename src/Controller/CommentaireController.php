<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \ConsoleTVs\Profanity\Builder;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $commentaires = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->findAll();
            
        $form = $this->createForm(CommentaireType::class);
        
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/back', name: 'app_commentaire_index_back', methods: ['GET'])]
    public function indexcmnt(Request $request): Response
    {
        $commentaires = $this->getDoctrine()
            ->getRepository(Commentaire::class)
            ->findAll();
            
        $form = $this->createForm(CommentaireType::class);
        
        return $this->render('commentaire/indexcmnt.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    // Function to handle form submission
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            /*
            $dictionaryPath = '/path/to/dictionary/file.txt';

// Set the dictionary for the Builder class
Builder::setDictionary($dictionaryPath);
*/
            $content = $commentaire->getContenu();
            $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $commentaire->setContenu($cleanedContenu);
            
    
            // Save commentaire to database
         //   $commentaire->setAuthor($this->getUser());
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            $this->addFlash('success', 'Your commentaire was submitted successfully.');
            return $this->redirectToRoute('app_commentaire_index');
        }
    
        return $this->render('commentaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{idCom}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
    #[Route('/{idCom}/back', name: 'app_commentaire_show_back', methods: ['GET'])]
    public function showback(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/showcmntr.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
    #[Route('/{idCom}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            // Check for bad words
            $badWords = ['bad', 'inappropriate', 'offensive', 'words'];
            $content = $commentaire->getContenu();
            foreach ($badWords as $badWord) {
                if (stripos($content, $badWord) !== false) {
                    $this->addFlash('danger', 'Your commentaire contains inappropriate language.');
                    return $this->redirectToRoute('app_commentaire_edit', ['idCom' => $commentaire->getIdCom()]);
                }
            }
    
            $entityManager->flush();
    
            $this->addFlash('success', 'Your commentaire was updated successfully.');
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    
    #[Route('/{idCom}/editback', name: 'app_commentaire_edit_back', methods: ['GET', 'POST'])]
    public function editback(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/editback.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    #[Route('/{idCom}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCom(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{idCom/delete}', name: 'app_commentaire_delete_back', methods: ['POST'])]
    public function deleteback(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCom(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index_back', [], Response::HTTP_SEE_OTHER);
    }
}
