<?php
namespace App\Controller\Front\Guardian;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\StudentRepository;
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
    public function redirectChildren( Guardian $guardian ,ParameterRepository $repo)
    { 
        return $this->render('Front/Guardian/children.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repo->find(1)
            ]);
    }

     /**
     * @Route("/teachers/{id}", name="guardian_teachers")
     */
    public function redirectTeacherss( Guardian $guardian ,ParameterRepository $repo)
    { 
        return $this->render('Front/Guardian/teachers.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repo->find(1)
            ]);
    }
    
   

    
}