<?php

namespace App\Controller;
use Knp\Snappy\Pdf;

use App\Entity\Reservation;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use MewesK\TwigDompdfBundle\MewesKTwigDompdfBundle;
use Doctrine\ORM\Query\Expr\Func;
use DateTimeImmutable;
use DateTime;





#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRes}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{idRes}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRes}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdRes(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }




    //impression
    #[Route('/{idRes}/print', name: 'app_reservation_print', methods: ['GET'])]
    public function generatePdf(Reservation $reservation): Response
    {
        // Create a new instance of Dompdf
        $dompdf = new Dompdf();
        
        // Fetch the reservation data from the database
        $reservationData = [
            ['Date début', $reservation->getDateDebut()->format('d/m/Y')],
            ['Date fin', $reservation->getDateFin()->format('d/m/Y')],
            ['Montant', $reservation->getMontant() . ' TND'],
        ];
        
        // Load the reservation data into an HTML template
        $html = '<html>
                    <head>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    </head>
                    <body>';
        
        // Format the reservation data as a Bootstrap-styled table
        $html .= '<table class="table table-bordered">';
        foreach ($reservationData as $row) {
            $html .= '<tr><th scope="row">' . $row[0] . '</th><td>' . $row[1] . '</td></tr>';
        }
        $html .= '</table>';
        
        $html .= '</body>
                </html>';
        
        // Load the HTML content into Dompdf and render as PDF
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Get the generated PDF content
        $pdf = $dompdf->output();
        
        // Create a Symfony response object with the PDF content
        $response = new Response($pdf);
        
        // Set the content type and disposition headers
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="reservation.pdf"');
        
        return $response;
    }



    
  
    function getReservationsInMonth(string $month, EntityManagerInterface $entityManager): array
    {
        // Récupération de l'année en cours
        $year = date('Y'); 
    
        // Création d'une instance de DateTime avec la date du premier jour du mois spécifié
        $startDate = new DateTime("$year-$month-01");
        $dateFormatted1 = $startDate->format('Y-m-d');
    
        // Récupération du nombre de jours dans le mois
        $numDays = $startDate->format('t');
    
        // Création d'une instance de DateTime avec la date du dernier jour du mois spécifié
        $endDate = new DateTime("$year-$month-$numDays");
        $dateFormatted2 = $endDate->format('Y-m-d');
    
        // Récupération des réservations qui commencent entre le premier et le dernier jour du mois
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.dateDebut >= :start AND r.dateDebut <= :end')
            ->setParameter('start', $dateFormatted1)
            ->setParameter('end', $dateFormatted2)
            ->getQuery()
            ->getResult();
    
        // Retourne un tableau de réservations
        return $reservations;
    }
    


    public function generatePdfByMonth(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le mois sélectionné
        $month = $request->get('month');
    
        // Récupérer toutes les réservations pour le mois sélectionné
        $reservations = $this->getReservationsInMonth($month, $entityManager);
    
        // Générer le contenu HTML du PDF en utilisant le template Twig
        $html = $this->renderView('reservation/index.html.twig', [        'reservations' => $reservations,    ]);
    
        // Initialiser une instance de Dompdf
        $dompdf = new Dompdf();
    
        // Ajouter un en-tête au PDF contenant le logo et le mois sélectionné
        $header = '<div style="text-align: center; margin-bottom: 20px;"></div>';
    
        // Récupérer la date correspondant au mois sélectionné
        $selectedMonth = $request->get('month');
        $date = date_create_from_format('n', $selectedMonth);
    
        // Ajouter le logo de l'entreprise dans l'en-tête
        $logo = '<div style="position: absolute; top: 20px; left: 40px; font-size: 24px; font-weight: bold; font-style: italic; color: white; z-index: -1;">
                Smart Wheels
            </div>
            <div style="position: absolute; top: 20px; left: 20px; font-size: 24px; font-weight: bold; font-style: italic; color: black;">
                <span style="color: #37a5a5;">Smart</span>Wheels
            </div>';
        $header .= $logo;
    
        // Ajouter le mois sélectionné dans l'en-tête
        $header .= '<h2 style="text-align: center; margin-top: 60px; margin-bottom: 20px;">Reservations for ' . $date->format('F') . '</h2>';
    
        // Ajouter le contenu HTML dans le PDF
        $dompdf->set_option('isRemoteEnabled', true);
        $html = '<html>
                    <head>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    </head>
                    <body>';
        $html .= $header;
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead style="background-color:#4169E1; color: white;"><tr><th>iRes</th><th>DateDebut</th><th>DateFin</th><th>Montant</th></tr></thead><tbody>';
        foreach ($reservations as $reservation) {
            $html .= '<tr><td>'. $reservation->getIdRes() .'</td><td>'. $reservation->getDateDebut()->format('d/m/Y') .'</td><td>'. $reservation->getDateFin()->format('d/m/Y') .'</td><td>'. $reservation->getMontant().'TND' .'</td></tr>';
        }
        $html .= '</tbody></table>';
    
        // Rendre le HTML en PDF en utilisant Dompdf
        $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

  
    $pdf = $dompdf->output();


    $response = new Response($pdf);


    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename="' . $date->format('F') . '.pdf"');

    return $response;
}

    





    
  
    

}
