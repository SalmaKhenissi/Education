<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Guardian;

use App\Repository\ClubRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\PictureRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\ParameterRepository;
use App\Repository\DescriptionRepository;
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
    public function redirectHome(ParameterRepository $repoP,EventRepository $repoE, PictureRepository $repoPi ,DescriptionRepository $repoD)
    {

        $list= $repoPi->findAll(); $pictures=[];
        foreach($list as $l)
        {
            $k=strtotime($l->getUpdatedAt()->format('Y-m-d H:i:s'));
            $pictures[$k]=$l;
        }

        krsort($pictures);$tabP=[];$i=0;
        foreach($pictures as $p)
        {
            $tabP[]=$p; $i = $i + 1 ;
            if(count($tabP)==7)
            {
                break;
            }
        }

        $listE= $repoE->findAll(); $events=[];
        foreach($listE as $l)
        {
            $k=strtotime($l->getTime()->format('Y-m-d H:i:s'));
            $events[$k]=$l;
        }

        krsort($events);$tabE=[];$i=0;
        foreach($events as $e)
        {
            $tabE[]=$e; $i = $i + 1 ;
            if(count($tabE)==4)
            {
                break;
            }
        }
        return $this->render('Front/Guest/home.html.twig', [
            'parameters' => $repoP->find(1),
            'service1' => $repoD->find(1),
            'service2' => $repoD->find(2),
            'service3' => $repoD->find(3),
            'service4' => $repoD->find(4),
            'pictures' => $tabP ,
            'events' => $tabE
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
