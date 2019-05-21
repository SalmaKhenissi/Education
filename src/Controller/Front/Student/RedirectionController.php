<?php
namespace App\Controller\Front\Student;

use App\Entity\Student;
use App\Repository\ExamRepository;
use App\Repository\CourseRepository;
use App\Repository\SeanceRepository;

use App\Repository\QuarterRepository;
use App\Repository\SectionRepository;
use App\Repository\DocumentRepository;
use App\Repository\ParameterRepository;
use App\Repository\SchoolYearRepository;
use App\Repository\ObservationRepository;
use App\Repository\StudentExamRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/timetable/{id}", name="student_timetable" , methods={"GET"})
     */
    public function redirectTimetable(Student $student ,ParameterRepository $repoP ,SeanceRepository $seanceRepository ,SectionRepository $repoS )
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);


        $seances=$seanceRepository->findBySection($section);
         $timetable=$seanceRepository->findTimeTable($seances);
        return$this->render('Front/Student/timetable.html.twig' , [
            'student' => $student ,
            'parameters' => $repoP->find(1) ,
            'section' => $section,
            'timetable' => $timetable,
            ]);
    }

    /**
     * @Route("/exams/{id}", name="student_exams" , methods={"GET"})
     */
    public function redirectExams(Student $student  ,ParameterRepository $repoP ,SectionRepository $repoS ,ExamRepository $examRepository )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $exams=$examRepository->findBySection($section);

        $tabC=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()== 2 || $e->getType()== 3 ))
            {
                $k=strtotime($e->getPassAt()->format('Y-m-d H:i:s')).strtotime($e->getStartAt()->format('H:i'));
                $tabC[$k]=$e;
            }

        }
        
        krsort($tabC);

        $tabE=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()== 4 || $e->getType()== 5))
            {
                $tabE[]=$e;
            }

        }
        $timetableExam=$examRepository->findTimeTable($tabE);

        return$this->render('Front/Student/exams.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'timetableExam' => $timetableExam ,
            'tabC' => $tabC
            ]);
    }

    /**
     * @Route("/discipline/{id}", name="student_discipline" , methods={"GET"})
     */
    public function redirectDiscipline(Student $student ,ParameterRepository $repoP ,SectionRepository $repoS)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();

        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        foreach($section->getSchoolYear()->getQuarters() as $q )
        {
            if($q->getnumber()==$quarter){$currentQuarter=$q;}
        }
        $start=$currentQuarter->getStartAt();
        $finish=$currentQuarter->getFinishAt();

        $tabA=[];   
        foreach($student->getDisciplines() as $d)
        {
            if( $d->getType()!='Présent' && $d->getDate()>=$start && $d->getDate()<=$finish )
            {   $k=strtotime($d->getDate()->format('Y-m-d H:i:s'));
                $tabA[$k]=$d;
            }
        }
        krsort($tabA);

        $tabP=[];   
        foreach($student->getPunishments() as $p)
        {
            if( $p->getDate()>=$start && $p->getDate()<=$finish )
            { $k=strtotime($p->getDate()->format('Y-m-d H:i:s'));
                $tabP[$k]=$p;
            }
        }

        krsort($tabP);

        return$this->render('Front/Student/discipline.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'disciplines' => $tabA ,
            'punishments' => $tabP ,
            ]);
    }

    /**
     * @Route("/notes/{id}", name="student_notes" , methods={"GET"})
     */
    public function redirectNotes(Student $student,QuarterRepository $repoQ ,SchoolYearRepository $repoSY ,CourseRepository $repoC ,ExamRepository $repoE ,StudentExamRepository $repoSE  ,ParameterRepository $repoP ,SectionRepository $repoS )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarterNumber=$repoP->find(1)->getQuarter();
        $quarter1=$repoQ->findQuarter($schoolYear ,1);
        $quarter2=$repoQ->findQuarter($schoolYear ,2);
        $quarter3=$repoQ->findQuarter($schoolYear ,3);
        $quarter=$repoQ->findQuarter($schoolYear ,$quarterNumber);

        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $exams=$repoE->findBySection($section);
        $courses=$repoC->findBySection($section);
        

            $today= new \DateTime('now'); 
        
            if($today >= $quarter1->getStartAt() && $today <= $quarter1->getCouncilDate() ) 
            { 
                $noteTable1=$repoSE->findNoteTable($exams,$student, 1);
                $noteTable=$noteTable1;
            }
            else if($today >= $quarter2->getStartAt() && $today <= $quarter2->getCouncilDate() ) 
            { 
                $noteTable1=$repoSE->findNoteTable($exams,$student, 1);
                $noteTable2=$repoSE->findNoteTable($exams,$student, 2);
                $noteTable=$noteTable2;
            }
            else if($today >= $quarter3->getStartAt()  ) 
            {   $noteTable1=$repoSE->findNoteTable($exams,$student, 1);
                $noteTable2=$repoSE->findNoteTable($exams,$student, 2);
                $noteTable3=$repoSE->findNoteTable($exams,$student, 3);

                $noteTable=$noteTable3; 
            }
            
        $tabA=[];
        $average1=$repoSE->countAverage($noteTable1,$courses);$tabA[]=$average1;  
        $average2=$repoSE->countAverage($noteTable2,$courses);$tabA[]=$average2;
        $average3=$repoSE->countAverage($noteTable3,$courses);$tabA[]=$average3;
        
        $averageG=($average1+$average2*2+$average3*2 )/5;$tabA[]=$averageG;

        /*$tabR=[];
        $rank1=$repoSE->findRank($student,$section ,$exams,$courses ,1);
        $rank2=$repoSE->findRank($student,$section ,$exams,$courses ,2);
        $rank3=$repoSE->findRank($student,$section ,$exams,$courses ,3);
        $rankG=$repoSE->findRank($student,$section ,$exams,$courses );*/
        
            
            
          /*  
                if($q->getNumber() == 1 ) 
                {
                    $tabA[]=$average1; //$tabR[]=$rank1;
                               
                 }
                else if($q->getNumber() == 2 ) 
                {
                    $tabA[]=$average1; //  $tabR[]=$rank1;
                    $tabA[]=$average2;// $tabR[]=$rank2;
                }
                else 
                {
                    $tabA[]=$average1;//$tabR[]=$rank1;
                    $tabA[]=$average2;//$tabR[]=$rank1;
                    $tabA[]=$average3;//$tabR[]=$rank1;
                    $tabA[]=$averageG;//$tabR[]=$rankG;
                    
                    
                }*/
            
        
        return$this->render('Front/Student/notes.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'noteTable' => $noteTable,
            ]);
    }

    /**
     * @Route("/obs/{id}", name="student_obs", methods={"GET"})
     */
    public function redirectObs(Student $student ,SectionRepository $repoS  ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $entityManager = $this->getDoctrine()->getManager();
       
        $entityManager->flush();


        $tab=[];
        foreach($section->getObservations() as $o)
        {  $k=strtotime($o->getPostedAt()->format('Y-m-d H:i:s'));
             $tab[$k]=$o;
        }
        krsort($tab);

        $obs=$paginator->paginate($tab, $request->query->getInt('page', 1), 5);
        return $this->render('Front/Student/observation.html.twig', [
            'obs' => $obs,
            'student' => $student,
            'section' => $section ,
            'parameters' => $param ,
        ]);
    }
}