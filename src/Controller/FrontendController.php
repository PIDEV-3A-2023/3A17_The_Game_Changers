<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Reservation;
use App\Form\VehiculeType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
//use App\Service\TwilioService;

/** 
 * @Route("/front")
 */
class FrontendController extends AbstractController
{
/*
    private $twilioService;
    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    */

    /** 
     * @Route("/", name="front_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $vehicules = $entityManager
            ->getRepository(Vehicule::class)
            ->findAll();
       
        return $this->render('frontend/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }
    /** 
     * @Route("/newfrontRes/{id}", name="app_front_res")
     */
    public function newReservation(Request $request, EntityManagerInterface $entityManager, $id): Response
    {


        $vehicules = $entityManager
            ->getRepository(Vehicule::class)
            ->find($id);
            

            $vehiculess = $entityManager
            ->getRepository(Vehicule::class)
            ->findAll();

        if (!$vehicules) {
            throw $this->createNotFoundException('Le véhicule n\'existe pas');
        }

        $reservation = new Reservation();
        $reservation->setIdV($vehicules);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingReservation = $entityManager
            ->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.idV = :idV')
            ->andWhere(':dateDebut BETWEEN r.dateDebut AND r.dateFin OR :dateFin BETWEEN r.dateDebut AND r.dateFin')
            ->setParameters([
                'idV' => $reservation->getIdV(),
                'dateDebut' => $reservation->getDateDebut(),
                'dateFin' => $reservation->getDateFin(),
            ])
            ->setMaxResults(1) // Limit the number of results to one
            ->getQuery()
            ->getOneOrNullResult();
        
    if ($existingReservation) {
        $form->addError(new FormError('Il existe déjà une réservation pour ces dates.'));
    } else {
        $entityManager->persist($reservation);
        $entityManager->flush(); 
    }
        }
     
       // $twilioService = new TwilioService('AC0ce74c8f65b20a8e927d7f39a8abe10f', '3d0ff5057e0962b0e69eec1afb2637bd', '+16204558085');
/*
// Envoyer un SMS au numéro de téléphone du client
$message = 'Votre réservation a été faite avec succès du ' . $dateDebut . ' au ' . $dateFin . ' avec un montant de ' . $reservation->getMontant() . '. Merci de choisir SmartWheelsTransport!';
$this->twilioService->sendSms('+21692265519', $message);
*/

        return $this->renderForm('formulaireRes.html.twig', [
            'reservation' => $reservation,
            'vehiculess' => $vehiculess,
            'form' => $form,
            
        ]);
    }


    





}