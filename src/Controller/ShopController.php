<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{

    public function getVehiclesByBrands(array $brands): array
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $vehicles = $entityManager->getRepository(Vehicule::class)->findAll();
    
        $filteredVehicles = [];
    
        foreach ($vehicles as $vehicle) {
            if (in_array($vehicle->getMarque(), $brands)) {
                $filteredVehicles[] = $vehicle;
            }
        }
    
        return $filteredVehicles;
    }


    //tri par  marque
    /**
     * @Route("/shop", name="shop")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marques = $request->request->get('test');
       

        if (!empty($marques)) {
            $vehicules = $this->getVehiclesByBrands($marques);
        } else {
            $vehicules = $entityManager->getRepository(Vehicule::class)->findAll();
        }

        return $this->render('shop/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }











    function extractNumbersFromString(string $str): array
    {
        // Supprime le premier caractère s'il s'agit d'un signe dollar ($)
        if (substr($str, 0, 1) === '$') {
            $str = substr($str, 1);
        }
    
        // Récupère les parties séparées par un tiret
        $parts = explode(' - ', $str);
    
        // Recherche les nombres les plus proches de chaque côté du tiret
        $leftNumber = $rightNumber = null;
        foreach ($parts as $part) {
            $matches = [];
            preg_match('/\d+/', $part, $matches);
            if (isset($matches[0])) {
                $number = intval($matches[0]);
                if ($leftNumber === null || $number < $leftNumber) {
                    $leftNumber = $number;
                }
                if ($rightNumber === null || $number > $rightNumber) {
                    $rightNumber = $number;
                }
            }
        }
    
        // Retourne les deux nombres dans un tableau trié
        return [$leftNumber, $rightNumber];
    }


    function filterVehiclesByPriceRange(array $numbers): array
{
    $minPrice = $numbers[0];
    $maxPrice = $numbers[1];

    $entityManager = $this->getDoctrine()->getManager();

    $qb = $entityManager->createQueryBuilder();
    $qb->select('v')
        ->from('App\Entity\Vehicule', 'v')
        ->where($qb->expr()->between('v.prix', $minPrice, $maxPrice));

    $query = $qb->getQuery();

    $vehicles = $query->getResult();

    return $vehicles;
}
//tri par prix

    /**
     * @Route("/prix", name="prix")
     */
    public function triParPrix(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prix = $request->request->get('prix');
       

        $pr=$this->extractNumbersFromString($prix);
      
            $vehicules = $entityManager->getRepository(Vehicule::class)->findAll();
  $vehicules=$this->filterVehiclesByPriceRange($pr);

        return $this->render('shop/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }


}
