<?php

namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
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
 * @Route("/profile/guardian/discipline")
 */
class DisciplineController extends AbstractController
{
     /**
     * @Route("/children/{id}", name="guardian_discipline_children")
     */
    public function redirectChildren( Guardian $guardian ,ParameterRepository $repoP , StudentRepository $repoS)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        return $this->render('Front/Guardian/Discipline/children.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'children' =>$repoS->findByGuardian($guardian,$schoolYear)

        ]);
    }

    
    /**
     * @Route("/attendance/{id}", name="guardian_discipline_attendance" , methods={"GET"})
     */
    public function redirectAttendance(Student $student ,ParameterRepository $repoP ,SectionRepository $repoS)
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
            if( $d->getType()!= 0 && $d->getDate()>=$start && $d->getDate()<=$finish )
            {   $k=strtotime($d->getDate()->format('Y-m-d H:i:s'));
                $tabA[$k]=$d;
            }
        }
        krsort($tabA);


        return$this->render('Front/Guardian/Discipline/attendance.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'guardian' => $student->getGuardian(),
            'disciplines' => $tabA ,
            ]);
    }

    /**
     * @Route("/punishment/{id}", name="guardian_discipline_punishment" , methods={"GET"})
     */
    public function redirectPunishment(Student $student ,ParameterRepository $repoP ,SectionRepository $repoS)
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

        $tabP=[];   
        foreach($student->getPunishments() as $p)
        {
            if( $p->getDate()>=$start && $p->getDate()<=$finish )
            { $k=strtotime($p->getDate()->format('Y-m-d H:i:s'));
                $tabP[$k]=$p;
            }
        }

        krsort($tabP);
        return$this->render('Front/Guardian/Discipline/punishment.html.twig', [
            'student' => $student ,
            'parameters' => $repoP->find(1),
            'guardian' => $student->getGuardian(),
            'punishments' => $tabP ,
            ]);
    }


    

   
}
