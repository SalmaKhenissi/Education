<?php

namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\ExamRepository;
use App\Repository\CourseRepository;
use App\Repository\QuarterRepository;
use App\Repository\SectionRepository;
use App\Repository\ParameterRepository;
use App\Repository\StudentExamRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian")
 */
class ResultController extends AbstractController
{
    /**
     * @Route("{id}/average", name="guardian_result_average", methods={"GET" ,"POST"})
     */
    public function findAverage(Guardian $guardian, StudentExamRepository $repoSE,CourseRepository $repoC ,ExamRepository $repoE ,QuarterRepository $repoQ , SectionRepository $repoS ,ParameterRepository $repoP , Request $request ): Response
    { 
        
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $quarterNumber=$repoP->find(1)->getQuarter();
        $quarter1=$repoQ->findQuarter($schoolYear ,1);
        $quarter2=$repoQ->findQuarter($schoolYear ,2);
        $quarter3=$repoQ->findQuarter($schoolYear ,3);
        $quarter=$repoQ->findQuarter($schoolYear ,$quarterNumber);

        foreach($guardian->getChildren() as $student)
        {       $result=[];
                $sections=$student->getSections();
                $section =$repoS->findByYear($sections,$schoolYear);

                $exams=$repoE->findBySection($section);
                $courses=$repoC->findBySection($section);
                
                $today= new \DateTime('now'); 
                $tabA=[]; $tabR=[];
                $decision = null ;
                
                if($today >= $quarter3->getCouncilDate()  ) 
                {   $noteTable1=$repoSE->findNoteTable($exams,$student, 1,$repoE);
                    $average1=$repoSE->countAverage($noteTable1,$courses);$tabA[1]=$average1;
                    //$rank1=$repoSE->findRank($student,$section ,$exams,$courses ,1, $repoE);$tabR[1]=$rank1;
                    
                    $noteTable2=$repoSE->findNoteTable($exams,$student, 2,$repoE);
                    $average2=$repoSE->countAverage($noteTable2,$courses);$tabA[2]=$average2; 
                // $rank2=$repoSE->findRank($student,$section ,$exams,$courses ,2, $repoE);$tabR[2]=$rank2; 

                    $noteTable3=$repoSE->findNoteTable($exams,$student, 3,$repoE);
                    $average3=$repoSE->countAverage($noteTable3,$courses);$tabA[3]=$average3;
                    //$rank3=$repoSE->findRank($student,$section ,$exams,$courses ,3, $repoE);$tabR[3]=$rank3;  

                    $averageG=($average1+$average2*2+$average3*2 )/5;$tabA[4]=$averageG;
                // $rankG=$repoSE->findRankG($student,$section ,$exams,$courses, $repoE);$tabR[4]=$rankG;
                if($averageG > 10) 
                { $decision = "Admis";}
                else { $decision = "Réfusé"; }
                }
                else if($today >= $quarter2->getCouncilDate()  ) 
                { 
                    $noteTable1=$repoSE->findNoteTable($exams,$student, 1,$repoE);
                    $average1=$repoSE->countAverage($noteTable1,$courses);$tabA[1]=$average1;
                    $rank1=$repoSE->findRank($student,$section ,$exams,$courses ,1, $repoE);$tabR[1]=$rank1; 

                    $noteTable2=$repoSE->findNoteTable($exams,$student, 2,$repoE); 
                    $average2=$repoSE->countAverage($noteTable2,$courses);$tabA[2]=$average2;
                    //$rank2=$repoSE->findRank($student,$section ,$exams,$courses ,2, $repoE);$tabR[2]=$rank2; 
                }
                else if($today >= $quarter1->getCouncilDate()  ) 
                { 
                    $noteTable1=$repoSE->findNoteTable($exams,$student, 1,$repoE);
                    $average1=$repoSE->countAverage($noteTable1,$courses);$tabA[1]=$average1;
                // $rank1=$repoSE->findRank($student,$section ,$exams,$courses ,1, $repoE);$tabR[1]=$rank1;
                }
                $result[]=$student; $result[]=$tabA; $result[]=$decision;
                $tabStudent[]=$result;
        }
        
            
        return$this->render('Front/Guardian/Result/results.html.twig', [
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1),
            'tabStudent' => $tabStudent
            // 'tabR' => $tabR ,
            ]);
      
    }
     /**
     * @Route("{id}/notes/{quarter}", name="guardian_result_notes", methods={"GET" ,"POST"})
     */
    public function findNotes(Student $student,$quarter ,SectionRepository $repoS ,ExamRepository $repoE, StudentExamRepository $repoSE ,ParameterRepository $repoP , Request $request ): Response
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $guardian = $student->getGuardian();


        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $exams=$repoE->findBySection($section);
        
        $noteTable=$repoSE->findNoteTable($exams,$student, $quarter,$repoE);

        return$this->render('Front/Guardian/Result/notes.html.twig', [
            'guardian' => $guardian ,
            'student' => $student,
            'parameters' => $repoP->find(1),
            'noteTable' => $noteTable ,
            'quarter' => $quarter
            ]);
        
    }

    

   
}
