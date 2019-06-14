<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Form\StudentType;
use App\Form\Section;
use App\Repository\UserRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\ParameterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/student")
 */
class StudentController extends AbstractController
{

    

    /**
     * @Route("/index", name="admin_student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository , Request $request ,PaginatorInterface $paginator): Response
    {   
        $firstName = $request->get('first');
        $lastName = $request->get('last');
        $section = $request->get('section');
        $schoolYear = $request->get('schoolYear');
        $students=$paginator->paginate($studentRepository->findByPram($firstName , $lastName , $section ,$schoolYear), 
                                       $request->query->getInt('page', 1),
                                       6
        );
        return $this->render('Admin/Student/index.html.twig', [
            'students' => $students,
        ]);
    }

   /**
     * @Route("/{id}/new", name="admin_student_new", methods={"GET","POST"})
     */
    public function new(Request $request , Guardian $guardian , UserRepository $userRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setRoles('ROLE_STUDENT');
            $student->setGuardian($guardian);

            $student->setImageName('inconnu');

            $student->setUsername($userRepository->generateUsername($student) );
            $student->setPassword($userRepository->generatePassword($student) );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');
            return $this->redirectToRoute('admin_guardian_show', ['id'=>$student->getGuardian()->getId()]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Student/new.html.twig', [
            'student' => $student,
            'guardian' =>$guardian,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show1/{id}", name="admin_student_show", methods={"GET"})
     */
    public function show(Student $student, StudentRepository $studentRepository , ParameterRepository $repoP , SectionRepository $repoS): Response
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);
        return $this->render('Admin/Student/show.html.twig', [
            'student' => $student ,
            'section'=> $section 
            
        ]);
    }
    /**
     * @Route("/show2/{id}", name="admin_student_show2", methods={"GET"})
     */
    public function show2(Student $student, StudentRepository $studentRepository, ParameterRepository $repoP , SectionRepository $repoS): Response
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);
        return $this->render('Admin/Guardian/show_child.html.twig', [
            'student' => $student  ,
            'section'=> $section
            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_student_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Student $student): Response
    {   
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('admin_student_index');
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Student/edit.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}/edit2", name="admin_student_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Student $student): Response
    {  
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student->setUpdatedAt(new \DateTime('now')); 

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            
            return $this->redirectToRoute('admin_guardian_show', [
                'id' => $student->getGuardian()->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Guardian/edit_child.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_student_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Student $student): Response
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');
        
        return $this->redirectToRoute('admin_guardian_show', ['id'=>$student->getGuardian()->getId()]);
    }
}
