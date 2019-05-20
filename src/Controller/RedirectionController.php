<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Guardian;

use App\Repository\ClubRepository;
use App\Repository\ImageRepository;
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
    public function redirectAdmin(TeacherRepository $repoT, StudentRepository $repoSt, SectionRepository $repoS, ClubRepository $repoC)
    {
        return $this->render('Admin/admin.html.twig', [
            'teachers' => $repoT->findAll(),
            'students' => $repoSt->findAll(),
            'sections' => $repoS->findAll(),
            'clubs' => $repoC->findAll()
        ]);
    }




    /**
     * @Route("/front/home", name="home_page")
     */
    public function redirectHome(ParameterRepository $repoP, PictureRepository $repoPi ,ImageRepository $repoI)
    {
        return $this->render('Front/Guest/home.html.twig', [
            'parameters' => $repoP->find(1),
            'slider1' => $repoI->find(1),
            'slider2' => $repoI->find(2),
            'slider3' => $repoI->find(3),
            'service1' => $repoI->find(4),
            'service2' => $repoI->find(5),
            'service3' => $repoI->find(6),
            'service4' => $repoI->find(7),
            'pictures' => $repoPi->findAll()
        ]);
    }

    /**
     * @Route("/profile/student/{id}", name="student_profile")
     */
    public function redirectStudent(Student  $student, ParameterRepository $repoP  , SectionRepository $repoS)
    {
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        return $this->render('Front/Student/Profile/profile.html.twig', [
            'student' => $student,
            'parameters' => $repoP->find(1) ,
            'section'=> $section,
        ]);
    }

    /**
     * @Route("/profile/teacher/{id}", name="teacher_profile")
     */
    public function redirectTeacher(Teacher $teacher, ParameterRepository $repo)
    {
        return $this->render('Front/Teacher/Profile/profile.html.twig', [
            'teacher' => $teacher,
            'parameters' => $repo->find(1)
        ]);
    }

    /**
     * @Route("/profile/guardian/{id}", name="guardian_profile")
     */
    public function redirectGuardian(Guardian $guardian, ParameterRepository $repo, StudentRepository $repoS)
    {
        return $this->render('Front/Guardian/Profile/profile.html.twig', [
            'guardian' => $guardian,
            'parameters' => $repo->find(1)
        ]);
    }
}
