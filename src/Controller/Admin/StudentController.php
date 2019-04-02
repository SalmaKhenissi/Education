<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\Guardian;
use App\Form\StudentType;
use App\Repository\UserRepository;
use App\Repository\StudentRepository;
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
        $students=$paginator->paginate($studentRepository->findByName($firstName , $lastName ), 
                                       $request->query->getInt('page', 1),
                                       12
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
        $choicesSexe = Student::SEXE ;
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setRoles('ROLE_STUDENT');
            $student->setGuardian($guardian);

            $sexe=$student->getSexe();
            $student->setSexe($choicesSexe[$sexe]);
            
            

            $student->setUsername($userRepository->generateUsername($student) );
            $student->setPassword($userRepository->generatePassword($student) );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('admin_student_index');
        }

        return $this->render('Admin/Student/new.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_student_show", methods={"GET"})
     */
    public function show(Student $student): Response
    {
        return $this->render('Admin/Student/show.html.twig', [
            'student' => $student,
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
            return $this->redirectToRoute('admin_student_index', [
                'id' => $student->getId(),
            ]);
        }

        return $this->render('Admin/Student/edit.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_student_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Student $student): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_student_index');
    }
}
