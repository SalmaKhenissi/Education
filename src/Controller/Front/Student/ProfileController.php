<?php

namespace App\Controller\Front\Student;

use App\Entity\Student;
use App\Form\User3Type;
use App\Form\Student2Type;
use App\Form\PWStudentType;
use App\Repository\SectionRepository;
use App\Repository\ParameterRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_STUDENT" , statusCode=404)
 * @Route("/profile/student/edit")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/password/{id}", name="student_password_edit", methods={"GET" ,"POST"})
     */
    public function editP(Student $student ,SectionRepository $repoS ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        
        $form = $this->createForm(PWStudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('student_profile' , [
                'id' => $student->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Student/Profile/editPassword.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
            'parameters' => $param ,
            'section' => $section ,
        ]);

      
    }
     /**
     * @Route("/data/{id}", name="student_data_edit", methods={"GET" ,"POST"})
     */
    public function editD(Student $student  ,SectionRepository $repoS ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        
        $form = $this->createForm(Student2Type::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $student->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('student_profile' , [
                'id' => $student->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Student/Profile/editData.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
            'parameters' => $param ,
            'section' => $section ,
        ]);
    }

    /**
     * @Route("/photo/{id}", name="student_photo_edit", methods={"GET" ,"POST"})
     */
    public function editPh(Student $student  ,SectionRepository $repoS ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        
        $form = $this->createForm(User3Type::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($student->getImageFile()!=null )
            {
                if($student->getImageName()!="inconnu")
                {
                unlink(getcwd().'\uploads\photos\\'.$student->getImageName());
                }
                $student->setImageName(null);
            }

            $student->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('student_profile' , [
                'id' => $student->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Student/Profile/editPhoto.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
            'parameters' => $param ,
            'section' => $section ,
        ]);
    }

    

   
}
