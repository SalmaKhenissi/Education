<?php

namespace App\Controller\Front\Teacher;

use App\Entity\Teacher;
use App\Form\Teacher2Type;
use App\Form\PWTeacherType;
use App\Repository\ParameterRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/edit")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/password/{id}", name="teacher_password_edit", methods={"GET" ,"POST"})
     */
    public function editP(Teacher $teacher  ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);

        
        $form = $this->createForm(PWTeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teacher->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('teacher_profile' , [
                'id' => $teacher->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Profile/editPassword.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);

      
    }
     /**
     * @Route("/data/{id}", name="teacher_data_edit", methods={"GET" ,"POST"})
     */
    public function editD(Teacher $teacher  ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);
        
        $form = $this->createForm(Teacher2Type::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($teacher->getImageFile()!=null )
            {   
                if($teacher->getImageName()!="inconnu")
                {
                unlink(getcwd().'\uploads\photos\\'.$teacher->getImageName());
                }
                $teacher->setImageName(null);
            }

            $teacher->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('teacher_profile' , [
                'id' => $teacher->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Profile/editData.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

    

   
}
