<?php
namespace App\Controller\Front\Student;

use App\Entity\Student;
use App\Repository\ParameterRepository;
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
     * @Route("/club/{id}", name="student_club")
     */
    public function redirectClub(Student $student ,ParameterRepository $repo )
    { 
        return$this->render('Front/Student/club.html.twig', [
            'student' => $student ,
            'parameters' => $repo->find(1)
            ]);
    }

    /**
     * @Route("/timetable/{id}", name="student_timetable")
     */
    public function redirectTimetable(Student $student ,ParameterRepository $repo )
    { 
        return$this->render('Front/Student/timetable.html.twig' , [
            'student' => $student ,
            'parameters' => $repo->find(1)
            ]);
    }

    /**
     * @Route("/exams/{id}", name="student_exams")
     */
    public function redirectExams(Student $student ,ParameterRepository $repo )
    { 
        return$this->render('Front/Student/exams.html.twig', [
            'student' => $student ,
            'parameters' => $repo->find(1)
            ]);
    }

    /**
     * @Route("/discipline/{id}", name="student_discipline")
     */
    public function redirectDiscipline(Student $student ,ParameterRepository $repo )
    { 
        return$this->render('Front/Student/discipline.html.twig', [
            'student' => $student ,
            'parameters' => $repo->find(1)
            ]);
    }

    /**
     * @Route("/notes/{id}", name="student_notes")
     */
    public function redirectNotes(Student $student ,ParameterRepository $repo )
    { 
        return$this->render('Front/Student/notes.html.twig', [
            'student' => $student ,
            'parameters' => $repo->find(1)
            ]);
    }
}