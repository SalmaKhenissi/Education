<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\UserRepository;
use App\Repository\SectionRepository;
use App\Repository\TeacherRepository;
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
 * @Route("/admin/teacher")
 */
class TeacherController extends AbstractController
{


    /**
     * @Route("/index", name="admin_teacher_index", methods={"GET"})
     */
    public function index(TeacherRepository $teacherRepository , Request $request ,PaginatorInterface $paginator): Response
    {
        $firstName = $request->get('first');
        $lastName = $request->get('last');
        $teachers=$paginator->paginate($teacherRepository->findByOption($firstName , $lastName) , 
                                       $request->query->getInt('page', 1),
                                       12
        );
        return $this->render('Admin/Teacher/index.html.twig', [
            'teachers' => $teachers ,
        ]);
    }

    /**
     * @Route("/new", name="admin_teacher_new", methods={"GET","POST"})
     */
    public function new(Request $request ,UserRepository $userRepository): Response
    {
        $teacher = new Teacher();
        $choicesSexe = Teacher::SEXE ;
        $choicesType = Teacher::TYPE ;

        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacher->setRoles('ROLE_TEACHER');

            $sexe=$teacher->getSexe();
            $teacher->setSexe($choicesSexe[$sexe]);

            $type=$teacher->getType();
            $teacher->setType($choicesType[$type]);


            $teacher->setUsername($userRepository->generateUsername($teacher) );
            $teacher->setPassword($userRepository->generatePassword($teacher) );
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_teacher_index');
        }

        return $this->render('Admin/Teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_teacher_show", methods={"GET"})
     */
    public function show(Teacher $teacher): Response
    { 
        
        
        return $this->render('Admin/Teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_teacher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Teacher $teacher): Response
    {   
        $form = $this->createForm(TeacherType::class, $teacher);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $teacher->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_teacher_index', [
                'id' => $teacher->getId(),
            ]);
        }

        
        return $this->render('Admin/Teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_teacher_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Teacher $teacher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($teacher);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_teacher_index');
    }

     /**
     * @Route("/sections/{id}", name="admin_teacher_sections", methods={"GET"})
     */
    public function section(Teacher $teacher , SectionRepository $sectionRepository): Response
    {   
        return $this->render('Admin/Teacher/sections.html.twig', [
            'sections' => $sectionRepository->findbyTeacher($teacher),
        ]);
    }
}
