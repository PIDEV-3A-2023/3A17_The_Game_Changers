<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
   #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $vehicules = $entityManager
            ->getRepository(Vehicule::class)
            ->findAll();

            foreach ($vehicules as $vehicule) {
                $count = $entityManager->getRepository(Reclamation::class)->createQueryBuilder('r')
                ->select('count(r.id)')
                ->andWhere('r.idV = :idV')
                ->setParameter('idV', $vehicule->getId())
                ->getQuery()
                ->getSingleScalarResult();
                if ($count > 3) {
                    
                    $vehicule->setIsBlocked(1);
                    $entityManager->persist($vehicule);
                    $entityManager->flush();

                }
                else{
                    $vehicule->setIsBlocked(0);
                    $entityManager->persist($vehicule);
                    $entityManager->flush();

                }
            }

        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    //ajouterrrr

/**
 * Retourne le nom de la couleur correspondant au code hexadécimal.
 *
 * @param string $hexCode Le code hexadécimal de la couleur.
 * @return string Le nom de la couleur.
 */


    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            if ($image) {
                $imageFileName = uniqid().'.'.$image->guessExtension();
                move_uploaded_file($image, $this->getParameter('images_directory').'/'.$imageFileName);
                $vehicule->setImage($imageFileName);
            }
            $vehicule->setCouleur($form['couleur']->getData());
            $entityManager->persist($vehicule);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }
    
//afficher
    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    //modifierrr
    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            if ($image) {
                $imageFileName = uniqid().'.'.$image->guessExtension();
                move_uploaded_file($image, $this->getParameter('images_directory').'/'.$imageFileName);
                $vehicule->setImage($imageFileName);
            }
            $entityManager->flush();
    
            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }
    
    

    //supprimer
    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }


    
}
