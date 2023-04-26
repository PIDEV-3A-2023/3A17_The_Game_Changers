<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
#[Route('/reclamation')]
class ReclamationController extends AbstractController
{ 
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/test', name: 'app_reclamation_rechercher')]
    public function search(Request $request, ReclamationRepository $reclamationRepository, SerializerInterface $serializer)
    {
        $query = $request->query->get('query');
        $reclamations = $reclamationRepository->createQueryBuilder('r')
        ->where('r.nom LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
        $json = $serializer->serialize($reclamations, 'json');
        return new JsonResponse($json);
    }


    #[Route('/chart', name: 'app_reclamation_chart', methods: ['GET'])]
    public function chart(ReclamationRepository $repo): Response
    {
        // Get the daily counts from the repository
        $dailyCounts = $repo->countByDay();
    
        return $this->render('reclamation/statistics.html.twig', [
            'dailyCounts' => $dailyCounts,
        ]);
    }
    #[Route('/stat', name: 'app_reclamation_stat', methods: ['GET'])]
    public function statisrics(ReclamationRepository $repo): Response
    {
        $count = $repo->countAll();
        $oldest = $repo->findOldest();
        $newest = $repo->findNewest();

        return $this->render('reclamation/stats.html.twig', [
            'count' => $count,
            'oldest' => $oldest,
            'newest' => $newest,
        ]);
    }

    
    #[Route('/front', name: 'app_reclamation_index_front', methods: ['GET'])]
    public function indexF(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
  
  /*  #[Route('/update/image', name: 'upload_image', methods: ['GET', 'POST'])]
    public function updateImage(Request $request,FlashBagInterface $flashBagInterface)
    {
        $image = $request->files->get("image");
        $image_name=$image->getClientOriginalName();
        $image->move($this->getParameter("image_directory"),$image_name);
        
        $reclamation=new reclamation();
        $reclamation->setImage($image_name);
        $entityManager =$this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();
        $flashMessage->add("success");
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
*/
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $reclamation->setDatecreation(new \DateTime());

        if ($form->isSubmitted()) {
            $image = $request->files->get("image");
            if ($image) {
                $image_name = $image->getClientOriginalName();
                $image->move($this->getParameter("image_directory"), $image_name);
                $reclamation->setImage($image_name);
         
            }


            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/newback.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }


   
    #[Route('/newfront', name: 'app_reclamation_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $reclamation->setDatecreation(new \DateTime());
    
        if ($form->isSubmitted()) {
            // check if a similar content exists
            $existingReclamation = $entityManager
                ->getRepository(Reclamation::class)
                ->findOneBy([
                    'contenu' => $reclamation->getContenu(),
                    'nom' => $reclamation->getNom(),
                    'prenom' => $reclamation->getPrenom(),
                    'adresse' => $reclamation->getAdresse(),
                ]);
    
            if ($existingReclamation) {
                // if the same reclamation exists, add a warning message
                $this->addFlash('warning', 'This reclamation has already been submitted.');
            } else {
                // if the same reclamation does not exist, persist and flush the new reclamation
                $entityManager->persist($reclamation);
                $entityManager->flush();
    
                $this->addFlash('success', 'The reclamation has been submitted.');
    
                return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

  
    
    
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $email = (new Email())
        ->from('SmartWheels@gmail.com')
        ->to('zaineb.benaziza@esprit.tn')
        ->subject('content has changed')
        ->text('check your data')
        ->html('reclamation');

    $mailer->send($email);
        if ($form->isSubmitted()) {
            $entityManager->flush();
            
          
            return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('back/{id}', name: 'app_reclamation_show_back', methods: ['GET'])]
    public function showback(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show_back.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
   
    
    

    
}    