<?php
namespace App\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Security\Core\User\User ;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class SecurityController extends AbstractController
{   
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils )
    {  $error= $authenticationUtils->getLastAuthenticationError();
       $lastUsername= $authenticationUtils->getLastUsername();

       
       return $this->render('login.html.twig', [
           'last_username' => $lastUsername ,
           'error' => $error

           ] );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils)
    { 
    }

}