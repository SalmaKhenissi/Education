<?php
namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
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
     * @Route("/children/{id}", name="guardian_children")
     */
    public function redirectChildren( Guardian $guardian ,ParameterRepository $repoP , StudentRepository $repoS)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        return $this->render('Front/Guardian/children.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'children' =>$repoS->findByGuardian($guardian,$schoolYear)

            ]);
    }

     /**
     * @Route("/teachers/{id}", name="guardian_teachers")
     */
    public function redirectTeacherss( Guardian $guardian ,ParameterRepository $repoP ,StudentRepository $repoS , SectionRepository $repoSec , TeacherRepository $repoT)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $children=$repoS->findByGuardian($guardian,$schoolYear);
        $tabSection=[];$i=0;
        foreach($children as $c)
        {
            $sections=$c->getSections();
            $tabSection[$i] =$repoSec->findByYear($sections,$schoolYear);
            $i++ ;
        }

        return $this->render('Front/Guardian/teachers.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'teachers' =>$repoT->findBySection($tabSection)
            ]);
    }
    
   

    
}