<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Guardian;

use App\Repository\PictureRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\ParameterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectionController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_dashbord")
     */
    public function redirectAdmin(TeacherRepository $repoT  , StudentRepository $repoSt , SectionRepository $repoS)
    {   
        return$this->render('Admin/admin.html.twig' ,[
            'teachers' => count($repoT->findAll()),
            'students' => count($repoSt->findAll()),
            'sections' => count($repoS->findAll())
        ]);
    }


    

    /**
     * @Route("/front/home", name="home_page")
     */
    public function redirectHome( ParameterRepository $repoP , PictureRepository $repoPi)
    { 
        return$this->render('Front/Guest/home.html.twig' ,[
            'parameters' => $repoP->find(1) , 
            'pictures' => $repoPi->findAll()
            ] );
    }

    /**
     * @Route("/profile/student/{id}", name="student_profile")
     */
    public function redirectStudent( Student  $student ,ParameterRepository $repo)
    { 
        return$this->render('Front/Student/dashbord.html.twig' , ['student' => $student ,'parameters' => $repo->find(1)]);
    }

     /**
     * @Route("/profile/teacher/{id}", name="teacher_profile")
     */
    public function redirectTeacher(Teacher $teacher,ParameterRepository $repo )
    { 
        return$this->render('Front/Teacher/dashbord.html.twig', ['teacher' => $teacher ,'parameters' => $repo->find(1)]);
    }

     /**
     * @Route("/profile/guardian/{id}", name="guardian_profile")
     */
    public function redirectGuardian(Guardian $guardian ,ParameterRepository $repo , StudentRepository $repoS)
    { 
        return$this->render('Front/Guardian/profile.html.twig', ['guardian' => $guardian,'parameters' => $repo->find(1) ]);
    }
}