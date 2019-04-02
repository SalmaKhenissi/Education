<?php
namespace App\Controller\Front\Student;

use App\Entity\Student;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_STUDENT" , statusCode=404)
 * @Route("/profile/student")
 */
class RedirectionController extends AbstractController
{


     /**
     * @Route("/informations/{id}", name="student_informations")
     */
    public function redirectTeacher(Student $student )
    { 
        return$this->render('Front/Student/profile.html.twig', ['student' => $student]);
    }

     /**
     * @Route("/timetable/{id}", name="student_timetable")
     */
    public function redirectGuardian(Student $student )
    { 
        return$this->render('Front/Student/timetable.html.twig' , ['student' => $student]);
    }
}