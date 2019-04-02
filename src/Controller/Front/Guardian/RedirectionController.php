<?php
namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian")
 */
class RedirectionController extends AbstractController
{


     /**
     * @Route("/informations/{$id}", name="guardian_informations")
     */
    public function redirectGuardian( Guardian $guardian)
    { 
        return $this->render('Front/Guardian/profile.html.twig',['guardian' => $guardian]);
    }

   

    
}