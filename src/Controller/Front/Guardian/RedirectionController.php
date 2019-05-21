<?php
namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;

use App\Repository\TeacherRepository;
use App\Repository\ParameterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian")
 */
class RedirectionController extends AbstractController
{


    

     /**
     * @Route("/teachers/{id}", name="guardian_teachers")
     */
    public function redirectTeachers( Guardian $guardian ,ParameterRepository $repoP ,StudentRepository $repoS , SectionRepository $repoSec , TeacherRepository $repoT)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $children=$repoS->findByGuardian($guardian,$schoolYear);
        $TabSea=[];
        foreach($children as $c)
        { 
            foreach($c->getSections() as $s)
            { 
                if($s->getSchoolYear()->getLibelle()==$schoolYear) 
                {   $seancesId=[];$seances=[];
                    foreach($s->getSeances() as $sea)
                    {   $id=$sea->getId();
                        if(!in_array($id ,$seancesId  ))
                        {   $seancesId[]=$id;
                            $seances[]=$sea;
                        }
                    }

                }
            }
            $TabSea[$s->getLibelle()]=$seances;
        }
        $teachers=[];
        foreach($TabSea as $k => $t)
        {
            $teachers[$k]=$repoT->findBySection($t, $repoT);
        }
        
        return $this->render('Front/Guardian/teachers.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'teachers' =>$teachers
            ]);
    }

    
    /**
     * @Route("/timetables/{id}", name="guardian_timetable" , methods={"GET"})
     */
    public function redirectTimetable(Guardian $guardian ,StudentRepository $studentRepository ,ParameterRepository $repoP ,SeanceRepository $seanceRepository ,SectionRepository $repoS )
    {   

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $children=$studentRepository->findByGuardian($guardian,$schoolYear);
        $tab=[];
        foreach($children as $c)
        {
            $sections=$c->getSections();
            $section =$repoS->findByYear($sections,$schoolYear);

            $seances=$seanceRepository->findBySection($section);
            $timetable=$seanceRepository->findTimeTable($seances);
            $tab[$c->getFirstName().' '.$c->getLastName()]=$timetable;
        }


        return$this->render('Front/Guardian/timetable.html.twig' , [
            'parameters' => $repoP->find(1) ,
            'tab' => $tab,
            'guardian' => $guardian
            ]);
    }
    
   

    
}