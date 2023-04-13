<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**  
 * @Route("/front")
 */
class FrontendController extends AbstractController
{
    /**  
     * @Route("/", name="front_index")
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig');
    }
     /**  
     * @Route("/lodddgin", name="logdin")
     */
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }
 
}
