<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VehiculeType;
use App\Entity\Vehicule;
use App\Entity\Reservation;
use App\Form\ReservationType;

class BackendController extends AbstractController
{
    #[Route('/back', name: 'app_backend')]
    public function index(): Response
    {
        return $this->render('backend/index.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }
}
