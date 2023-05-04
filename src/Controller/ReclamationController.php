<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repository\SuiviReclamationRepository;
use App\Repository\RatingRepository;
use App\Repository\VehiculeRepository;
use App\Entity\Rating;
use App\Entity\Vehicule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamation;
use App\Entity\SuiviReclamation;
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
use Consoletvs\Profanity\ProfanityFilter;

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
    #[Route('/tri', name: 'app_reclamation_index_tri', methods: ['GET'])]
public function tri(EntityManagerInterface $entityManager): Response
{
    $reclamations = $entityManager
        ->getRepository(Reclamation::class)
        ->createQueryBuilder('r')
        ->where('r.datecreation >= :date')
        ->setParameter('date', new \DateTime('-7 days'))
        ->getQuery()
        ->getResult();

    return $this->render('reclamation/indextri.html.twig', [
        'reclamations' => $reclamations,
    ]);
}
#[Route('/triinverse', name: 'app_reclamation_index_tri_inv', methods: ['GET'])]
public function triInv(EntityManagerInterface $entityManager): Response
{
    $reclamations = $entityManager
        ->getRepository(Reclamation::class)
        ->createQueryBuilder('r')
        ->where('r.datecreation < :date')
        ->setParameter('date', new \DateTime('-7 days'))
        ->getQuery()
        ->getResult();

    return $this->render('reclamation/indextri2.html.twig', [
        'reclamations' => $reclamations,
    ]);
}

    #[Route('/test', name: 'app_reclamation_rechercher')]
    public function search(Request $request, ReclamationRepository $reclamationRepository, SerializerInterface $serializer)
    {
        $query = $request->get('query');
        $reclamations = $reclamationRepository->createQueryBuilder('r')
        ->where('r.nom LIKE :query')
        ->setParameter('query',$query . '%')
        ->getQuery()
        ->getResult();
        $json = $serializer->serialize($reclamations, 'json');
        return new JsonResponse($json);
    }

/*
    #[Route('/chart', name: 'app_reclamation_chart', methods: ['GET'])]
    public function chart(ReclamationRepository $repo): Response
    {
        // Get the daily counts from the repository
        $dailyCounts = $repo->countByDay();
    
        return $this->render('reclamation/stats.html.twig', [
            'dailyCounts' => $dailyCounts,
        ]);
    }
    */
    #[Route('/stat', name: 'app_reclamation_stat', methods: ['GET'])]
    public function statistics(ReclamationRepository $reclamationRepo, SuiviReclamationRepository $suiviRepo): Response
    {
        $count = $reclamationRepo->countAll();
        $oldest = $reclamationRepo->findOldest();
        $newest = $reclamationRepo->findNewest();
        $suivi = $suiviRepo->countAll();
        $dailyCounts = $reclamationRepo->countByDay();
        return $this->render('reclamation/stats.html.twig', [
            'count' => $count,
            'oldest' => $oldest,
            'newest' => $newest,
            'suivi' => $suivi,
            'dailyCounts' => $dailyCounts,
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
#[Route('/export-reclamations', name: 'export_reclamations')]
public function exportReclamations(ReclamationRepository $reclamationRepository): Response
{
    // Get the data from the repository
    $reclamations = $reclamationRepository->findAll();
    
    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    
    // Set the title of the worksheet
    $spreadsheet->getActiveSheet()->setTitle('Reclamations');
    
    // Set the header row
    $spreadsheet->getActiveSheet()
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Nom')
        ->setCellValue('C1', 'Prenom')
        ->setCellValue('D1', 'Adresse')
        ->setCellValue('E1', 'Contenu')
        ->setCellValue('F1', 'Date de création');
       
    
    // Add the data rows
    $row = 2;
    foreach ($reclamations as $reclamation) {
        $spreadsheet->getActiveSheet()
            ->setCellValue('A' . $row, $reclamation->getId())
            ->setCellValue('B' . $row, $reclamation->getNom())
            ->setCellValue('C' . $row, $reclamation->getPrenom())
            ->setCellValue('D' . $row, $reclamation->getAdresse())
            ->setCellValue('E' . $row, $reclamation->getContenu())
            ->setCellValue('F' . $row, $reclamation->getDatecreation()->format('Y-m-d'));
            
        $row++;
    }
    
    // Create a new Xlsx writer and write the data to a file
    $writer = new Xlsx($spreadsheet);
    $filePath = 'reclamations.xlsx';
    $writer->save($filePath);
    
    // Return the file as a response
    $response = new BinaryFileResponse($filePath);
    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'reclamations.xlsx');
    return $response;
}
   




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


   
    #[Route('/{id}/newfront', name: 'app_reclamation_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation,);
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
                // check if the reclamation text contains bad words
            
                $id = $request->attributes->get('id');
                $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($reclamation->getContenu())->filter();
                $reclamation->setContenu($cleanedContenu);
                $reclamation->setIdV($id);
                $reclamation->setEtat(0);
                $entityManager->persist($reclamation);
                $entityManager->flush();
                $count = $entityManager->getRepository(Reclamation::class)->createQueryBuilder('r')
                ->select('count(r.id)')
                ->andWhere('r.idV = :idV')
                ->setParameter('idV', $id)
                ->getQuery()
                ->getSingleScalarResult();

                if ($count > 3) {
                    $veh = $entityManager->getRepository(Vehicule::class)->findOneById($id);
                    $veh->setIsBlocked(1);
                    $entityManager->persist($veh);
                    $entityManager->flush();

                }
                
    
                $this->addFlash('success', 'The reclamation has been submitted.');
    
                return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
            }}
        
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

  
    
    
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET','POST'])]
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET','POST'])]
    public function show(Request $request,Reclamation $reclamation,SuiviReclamationRepository $repo,RatingRepository $ratingrepo): Response
    {
        $check=0;
        $Suivi = new SuiviReclamation();
        $Suivi = $repo->findByReclamationId($reclamation->getId());
        $rating = new Rating();
        $form = $this->createFormBuilder($rating)
            ->add('note', ChoiceType::class, [
                'label' => 'Rating',
                'choices' => [
                    '1 star' => 1,
                    '2 stars' => 2,
                    '3 stars' => 3,
                    '4 stars' => 4,
                    '5 stars' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->getForm();
    
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the rating
            $rating->setUser(1);
            $rating->setIdSuivi($Suivi);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();
            
            // AJAX call to update the average rating
            $response = new JsonResponse();
            $ratings = $ratingrepo->createQueryBuilder('r')
                ->select('AVG(r.note) as average_rating')
                ->andWhere('r.idSuivi = :id')
                ->setParameter('id', $Suivi->getId())
                ->getQuery()
                ->getSingleScalarResult();
            $averageRating = round($ratings, 1);
            $response->setData(['average_rating' => $averageRating]);
            return $response;
        }
    
        if($Suivi){
            $check=1;
            $ratings = $ratingrepo->createQueryBuilder('r')
                ->select('AVG(r.note) as average_rating')
                ->andWhere('r.idSuivi = :id')
                ->setParameter('id', $Suivi->getId())
                ->getQuery()
                ->getSingleScalarResult();
            $averageRating = round($ratings, 1);
            return $this->render('reclamation/show.html.twig', [
                'reclamation' => $reclamation,
                'reponse'=>$Suivi,
                'check'=>$check,
                'rating_form' => $form->createView(),
                'average_rating' => $averageRating,
            ]);
        }
    
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'check'=>$check,
            'rating_form' => $form->createView(),
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
        dd($request);   
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('back/{id}', name: 'app_reclamation_show_back', methods: ['GET'])]
    public function showback(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show_back.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    
    
    

    
}    