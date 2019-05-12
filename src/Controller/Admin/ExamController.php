<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Exam;
use App\Form\ExamType;
use App\Entity\Section;
use App\Form\Exam2Type;
use App\Repository\ExamRepository;
use App\Repository\RoomRepository;
use App\Repository\CourseRepository;
use App\Repository\QuarterRepository;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/exam")
 */
class ExamController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="admin_exam_index", methods={"GET"})
     */
    public function index(Section $section ,ExamRepository $examRepository , Request $request): Response
    {
        $exams=$examRepository->findBySection($section);

        $course = $request->get('course');
        $type = $request->get('type');
        $quarter = $request->get('quarter');

        $examsFiltred=$examRepository->findByOptions($course ,$type , $quarter);
        
        return $this->render('Admin/Exam/index.html.twig', [
            'exams' => $examsFiltred  ,
            'id' => $section->getId() ,
        ]);
    }

    /**
     * @Route("/new/{id}", name="admin_exam_new", methods={"GET","POST"})
     */
    public function new(Section $section ,Request $request ,CourseRepository $courseRepository ,QuarterRepository $quarterRepository): Response
    {
        $exam = new Exam();
        $choicesType = Exam::TYPE ;
        $exam->setSection($section); 
        $form = $this->createForm(ExamType::class, $exam, [
            'section' => $section,
            'courseRepository' => $courseRepository ,
            'quarterRepository' => $quarterRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $type=$exam->getType();
            $exam->setType($choicesType[$type]);
            if($type=='Synthése1' || $type=='Synthése2' )
            {$exam->setCoefficient(2); }
            else{$exam->setCoefficient(1);}

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();

            return $this->redirectToRoute('admin_exam_new2', [
                'id' => $exam->getId(),
            ]);
        }

        return $this->render('Admin/Exam/new.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new2/{id}", name="admin_exam_new2", methods={"GET","POST"})
     */
    public function new2(Request $request, Exam $exam ,RoomRepository $roomRepository ,TeacherRepository $teacherRepository , ExamRepository $examRepository): Response
    {
        
        $form = $this->createForm(Exam2Type::class, $exam, [
            'exam' => $exam,
            'teacherRepository' => $teacherRepository ,
            'roomRepository' => $roomRepository ,
            'examRepository' => $examRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_section_show', [
                'id' => $exam->getSection()->getId(),
            ]);
        }
        /*else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        }*/

        return $this->render('Admin/Exam/new_2.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_exam_show", methods={"GET"})
     */
    public function show(Exam $exam): Response
    {
        return $this->render('Admin/Exam/show.html.twig', [
            'exam' => $exam,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_exam_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exam $exam ,CourseRepository $courseRepository ,QuarterRepository $quarterRepository): Response
    {
        $choicesType = Exam::TYPE ;
        $form = $this->createForm(ExamType::class, $exam, [
            'section' => $exam->getSection(),
            'courseRepository' => $courseRepository ,
            'quarterRepository' => $quarterRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type=$exam->getType();
            $exam->setType($choicesType[$type]);
            if($type=='Synthése1' || $type=='Synthése2' )
            {$exam->setCoefficient(2); }
            else{$exam->setCoefficient(1);}

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_exam_edit2', [
                'id' => $exam->getId(),
            ]);
        }

        return $this->render('Admin/Exam/edit.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit2", name="admin_exam_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Exam $exam ,RoomRepository $roomRepository ,TeacherRepository $teacherRepository ,ExamRepository $examRepository): Response
    {
        $form = $this->createForm(Exam2Type::class, $exam, [
            'exam' => $exam,
            'teacherRepository' => $teacherRepository ,
            'roomRepository' => $roomRepository ,
            'examRepository' => $examRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_exam_index', [
                'id' => $exam->getSection()->getId(),
            ]);
        }
        /*else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        }*/

        return $this->render('Admin/Exam/edit_2.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_exam_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exam $exam): Response
    {
            $id=$exam->getSection()->getId();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        

        return $this->redirectToRoute('admin_exam_index', [
            'id' => $id,
        ]);
    }
}
