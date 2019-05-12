<?php
namespace App\Controller\Front\Teacher;

use App\Entity\Teacher;
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
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher")
 */
class RedirectionController extends AbstractController
{


     /**
     * @Route("/timetable/{id}", name="teacher_timetable")
     */
    public function redirectTimeTabel( Teacher $teacher ,ParameterRepository $repoP , SeanceRepository  $seanceRepository)
    {   
        $param=$repoP->find(1);
        $seances=$teacher->getSeances();
        $tab=[];
        foreach($seances as $s){
            if($s->getSection()->getSchoolYear()->getLibelle()==$param->getSchoolYear())
            {
                $tab[]=$s;
            }
        }
        $timeTable=$seanceRepository->findTimeTable($tab);
        
        return $this->render('Front/Teacher/timetable.html.twig',[
            'teacher' => $teacher ,
            'parameters' => $param ,
            'timetable' => $timeTable

            ]);
    }

    
   

    
}