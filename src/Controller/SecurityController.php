<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    public function __construct()
    {
        // Empty constructor
    }
/** 
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {
        $Utilisateur=new Utilisateur();
        $form=$this->createForm(RegistrationType::class,$Utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            
            $manager->persist($Utilisateur);
            $manager->flush();
        }
        return $this->render('registration.html.twig',['form' => $form->createView()]);
    }
    /** 
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
    }
}