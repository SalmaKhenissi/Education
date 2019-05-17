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
    public function redirectTimetable(Student $student ,ObservationRepository $observationRepository ,DocumentRepository $documentRepository ,ParameterRepository $repoP ,SeanceRepository $seanceRepository ,SectionRepository $repoS )
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $nb=$documentRepository->findNotifications($section);
        $nb1=$observationRepository->findNotifications($section);

        $seances=$seanceRepository->findBySection($section);
         $timetable=$seanceRepository->findTimeTable($seances);
        return$this->render('Front/Student/timetable.html.twig' , [
            'student' => $student ,
            'parameters' => $repoP->find(1) ,
            'section' => $section,
            'timetable' => $timetable,
            'nb' => $nb ,
            'nb1' => $nb1
            ]);
    }

    /**
     * @Route("/exams/{id}", name="student_exams" , methods={"GET"})
     */
    public function redirectExams(Student $student ,ObservationRepository $observationRepository ,DocumentRepository $documentRepository ,ParameterRepository $repoP ,SectionRepository $repoS ,ExamRepository $examRepository )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $exams=$examRepository->findBySection($section);

        $tabC=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()=='Controle1' || $e->getType()=='Controle2'))
            {
                $k=date('n',strtotime($e->getPassAt()->format('Y-m-d'))).date('d',strtotime($e->getPassAt()->format('Y-m-d')));
                $tabC[$k]=$e;
            }

        }
        
        krsort($tabC);

        $tabE=[];
        foreach($exams as $e)
        {
            if($e->getQuarter()->getNumber()==$quarter && ($e->getType()=='Synthése1' || $e->getType()=='Synthése2'))
            {
                $tabE[]=$e;
            }

        }
        $timetableExam=$examRepository->findTimeTable($tabE);

        $nb=$documentRepository->findNotifications($section);
        $nb1=$observationRepository->findNotifications($section);

        return$this->render('Front/Student/exams.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'nb' => $nb ,
            'nb1' => $nb1 ,
            'timetableExam' => $timetableExam ,
            'tabC' => $tabC
            ]);
    }

    /**
     * @Route("/discipline/{id}", name="student_discipline" , methods={"GET"})
     */
    public function redirectDiscipline(Student $student ,ObservationRepository $observationRepository ,DocumentRepository $documentRepository ,ParameterRepository $repoP ,SectionRepository $repoS)
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

        $tab=[];
        foreach($section->getSeances() as $seance)
        {   
            foreach($seance->getDisciplines() as $d)
            {
                if($d->getStudent()==$student && $d->getType()!='Présent' && $d->getDate()>=$start && $d->getDate()<=$finish )
                {
                    $tab[]=$d;
                }
            }

        }
        $sorted=[];
        foreach($tab as $d)
        {
            $k=date('n',strtotime($d->getDate()->format('Y-m-d'))).date('d',strtotime($d->getDate()->format('Y-m-d')));
            $sorted[$k]=$d;
        }
        krsort($sorted);

        $nb=$documentRepository->findNotifications($section);
        $nb1=$observationRepository->findNotifications($section);
        return$this->render('Front/Student/discipline.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'disciplines' => $sorted ,
            'nb' => $nb,
            'nb1' => $nb1
            ]);
    }

    /**
     * @Route("/notes/{id}", name="student_notes" , methods={"GET"})
     */
    public function redirectNotes(Student $student,QuarterRepository $repoQ ,SchoolYearRepository $repoSY ,CourseRepository $repoC ,ExamRepository $repoE ,StudentExamRepository $repoSE ,ObservationRepository $repoO ,DocumentRepository $repoD ,ParameterRepository $repoP ,SectionRepository $repoS )
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarter=$repoP->find(1)->getQuarter();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $exams=$repoE->findBySection($section);
        $courses=$repoC->findBySection($section);

        $noteTable1=$repoSE->findNoteTable($exams,$student, 1);
        $noteTable2=$repoSE->findNoteTable($exams,$student, 2);
        $noteTable3=$repoSE->findNoteTable($exams,$student, 3);

        $tabA=[];
        $average1=$repoSE->countAverage($noteTable1 ,$courses);
        $average2=$repoSE->countAverage($noteTable2,$courses);
        $average3=$repoSE->countAverage($noteTable3,$courses);
        
        $averageG=($average1+$average2*2+$average3*2 )/5;

        $tabR=[];
        $rank1=$repoSE->findRank($student,$section ,$exams,$courses ,1);
        $rank2=$repoSE->findRank($student,$section ,$exams,$courses ,2);
        $rank3=$repoSE->findRank($student,$section ,$exams,$courses ,3);
        $rankG=$repoSE->findRank($student,$section ,$exams,$courses );

        $objectYear=$repoSY->findByLibelle($schoolYear);
        $quarters=$repoQ->findBySchoolYear($objectYear);
        foreach($quarters as $q )
        {
            if($q->getNotesDisplay() == 1 ) 
            {
                if($q->getNumber() == 1 ) 
                {
                    $noteTable=$noteTable1;
                }
                else if($q->getNumber() == 2 ) 
                {
                    $noteTable=$noteTable2;
                }
                else 
                {
                    $noteTable=$noteTable3;
                }
            }
            
            if($q->getResultDisplay() == 1 ) 
            {
                if($q->getNumber() == 1 ) 
                {
                    $tabA[]=$average1; $tabR[]=$rank1;
                               
                 }
                else if($q->getNumber() == 2 ) 
                {
                    $tabA[]=$average1;   $tabR[]=$rank1;
                    $tabA[]=$average2; $tabR[]=$rank2;
                }
                else 
                {
                    $tabA[]=$average1;$tabR[]=$rank1;
                    $tabA[]=$average2;$tabR[]=$rank1;
                    $tabA[]=$average3;$tabR[]=$rank1;
                    $tabA[]=$averageG;$tabR[]=$rankG;
                    
                    
                }
            }
        }

        $nb=$repoD->findNotifications($section);
        $nb1=$repoO->findNotifications($section);
        
        return$this->render('Front/Student/notes.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'section' => $section,
            'nb' =>$nb ,
            'nb1'=> $nb1 ,
            'noteTable' => $noteTable ,
            'tabA' => $tabA ,
            'tabR' => $tabR
            ]);
    }

    /**
     * @Route("/obs/{id}", name="student_obs", methods={"GET"})
     */
    public function redirectObs(Student $student ,SectionRepository $repoS  ,DocumentRepository $documentRepository ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $entityManager = $this->getDoctrine()->getManager();
        foreach($section->getObservations() as $o)
        {   
            $o->setViewed(true);
            
        }
        $entityManager->flush();

        $nb=$documentRepository->findNotifications($section);

        $tab=[];$nb1=0;
        foreach($section->getObservations() as $o)
        {   $tab[]=$o;
            if($o->getViewed()==0)
            { $nb1++;}
        }
        
        $sorted=[];
        foreach($tab as $o) 
        {
            $k=date('n',strtotime($o->getPostedAt()->format('Y-m-d'))).date('d',strtotime($o->getPostedAt()->format('Y-m-d')));
            $sorted[$k]=$o;
        }
        krsort($sorted);

        $obs=$paginator->paginate($sorted, $request->query->getInt('page', 1), 5);
        return $this->render('Front/Student/observation.html.twig', [
            'obs' => $obs,
            'student' => $student,
            'section' => $section ,
            'parameters' => $param ,
            'nb' => $nb,
            'nb1' => $nb1
        ]);
    }
}