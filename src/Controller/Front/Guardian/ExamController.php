<?php

namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\ExamRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\ParameterRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian/exam")
 */
class ExamController extends AbstractController
{
     /**
     * @Route("/children/{id}", name="guardian_exam_children")
     */
    public function redirectChildren( Guardian $guardian ,ParameterRepository $repoP , StudentRepository $repoS)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        return $this->render('Front/Guardian/Exam/children.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'children' =>$repoS->findByGuardian($guardian,$schoolYear)

        ]);
    }

    
    /**
     * @Route("/timetable/{id}", name="guardian_exam_timetable" , methods={"GET"})
     */
    public function redirectTimetable(Student $student  ,ParameterRepository $repoP ,SectionRepository $repoS ,ExamRepository $examRepository )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);
        $guardian=$student->getGuardian();

        $exams=$examRepository->findBySection($section);

        $tabE=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()== 4 || $e->getType()== 5))
            {
                $tabE[]=$e;
            }

        }
        $timetableExam=$examRepository->findTimeTable($tabE);

        return$this->render('Front/Guardian/Exam/timetable.html.twig', [
            'parameters' => $repoP->find(1),
            'guardian' => $guardian,
            'timetableExam' => $timetableExam 
            ]);
    }

    /**
     * @Route("/list/{id}", name="guardian_exam_list" , methods={"GET"})
     */
    public function redirectList(Student $student  ,ParameterRepository $repoP ,SectionRepository $repoS ,ExamRepository $examRepository )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);
        $guardian=$student->getGuardian();

        $exams=$examRepository->findBySection($section);

        $tabC=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()== 2 || $e->getType()== 3 ))
            {
                $k=strtotime($e->getPassAt()->format('Y-m-d H:i:s')).strtotime($e->getStartAt()->format('H:i'));
                $tabC[$k]=$e; dump($tabC);
            }

        }
        krsort($tabC);

        return$this->render('Front/Guardian/Exam/list.html.twig', [
            'parameters' => $repoP->find(1),
            'guardian' => $guardian,
            'tabC' => $tabC 
            ]);
    }

    

   
}
