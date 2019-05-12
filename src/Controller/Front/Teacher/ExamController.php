<?php

namespace App\Controller\Front\Teacher;

use App\Entity\Exam;
use App\Form\NoteType;
use App\Entity\Teacher;
use App\Form\ExamType3;
use App\Entity\ExamSearch;
use App\Entity\StudentExam;
use App\Form\ExamSearchType;
use App\Form\StudentExamType;
use App\Repository\ExamRepository;
use App\Repository\RoomRepository;
use App\Repository\CourseRepository;
use App\Repository\SeanceRepository;
use App\Repository\QuarterRepository;
use App\Repository\SectionRepository;
use App\Repository\ParameterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/exams")
 */
class ExamController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="teacher_exam_index", methods={"GET"})
     */
    public function index(Teacher $teacher,ExamRepository $examRepository  ,SectionRepository $sectionRepository ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   
        $param=$repoP->find(1);
        $sections=$sectionRepository->findbyTeacher($teacher);
        
        $search = new ExamSearch();
        $form = $this->createForm(ExamSearchType::class , $search ,[
            'sections' => $sections 
        ]);
        $form->handleRequest($request);

        $exams=$paginator->paginate($examRepository->findbyTeacher($sections,$teacher ,$search), 
                                        $request->query->getInt('page', 1),
                                        5
        );
        return $this->render('Front/Teacher/Exam/index.html.twig', [
            'exams' => $exams,
            'teacher' => $teacher,
            'parameters' => $param ,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new/{id}", name="teacher_exam_new", methods={"GET","POST"})
     */
    public function new(Teacher $teacher ,Request $request ,ParameterRepository $repoP ,CourseRepository $courseRepository ,SectionRepository $sectionRepository , RoomRepository $roomRepository, QuarterRepository $quarterRepository , SeanceRepository $seanceRepository ): Response
    {   
        $param=$repoP->find(1);

        $choicesType = Exam::TYPE ;
        $exam = new Exam();

        $sections=$sectionRepository->findByTeacher($teacher);

        $form = $this->createForm(ExamType3::class, $exam, [
            'sections' => $sections ,
            'quarterRepository' => $quarterRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type=$exam->getType();
            $exam->setType($choicesType[$type]);
            if($type=='Synthése1' || $type=='Synthése2' )
            {$exam->setCoefficient(2); }
            else{$exam->setCoefficient(1);}

            $exam->addTeacher($teacher);

            $courses=$courseRepository->findBySection($exam->getSection());
            foreach($courses as $c)
            {
                if($c->getLibelle()==$teacher->getSpecialty())
                {
                    $exam->setCourse($c);
                }
            }

            $seances=$seanceRepository->findByTeaching($teacher,$exam->getSection());
            $room=$roomRepository->findByDay($seances ,$exam);
            $exam->setRoom($room);

            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();
            
            foreach($exam->getSection()->getStudents() as $s)
            {
                $StudentExam = new StudentExam($exam,$s);
                $StudentExam->setNote(0);
                $StudentExam->setExam($exam);
                $entityManager->persist($StudentExam);
                $entityManager->flush();
            }
            
            

            $id=$teacher->getId();
            return $this->redirectToRoute('teacher_exam_index', [
                'id' => $id,
            ]);
        }

        return $this->render('Front/Teacher/Exam/new.html.twig', [
            'exam' => $exam,
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("{teacher}/show/{id}", name="teacher_exam_show", methods={"GET"})
     */
    public function show(Teacher $teacher ,Exam $exam ,ParameterRepository $repoP ): Response
    {   
        
       
        return $this->render('Front/Teacher/Exam/show.html.twig', [
            'exam' => $exam,
            'teacher' => $teacher,
            'parameters' => $repoP->find(1) 
        ]);
    }

    /**
     * @Route("{teacher}/edit/{id}", name="teacher_exam_edit", methods={"GET","POST"})
     */
    public function edit(Teacher $teacher, Exam $exam,Request $request ,ParameterRepository $repoP ,SectionRepository $sectionRepository , RoomRepository $roomRepository, QuarterRepository $quarterRepository , SeanceRepository $seanceRepository): Response
    {  $param=$repoP->find(1);
        $choicesType = Exam::TYPE ;
        $form = $this->createForm(ExamType3::class, $exam , [
            'sections' => $sections ,
            'quarterRepository' => $quarterRepository 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $type=$exam->getType();
            $exam->setType($choicesType[$type]);
            if($type=='Synthése1' || $type=='Synthése2' )
            {$exam->setCoefficient(2); }
            else{$exam->setCoefficient(1);}

            $seances=$seanceRepository->findByTeaching($teacher,$exam->getSection());
            $room=$roomRepository->findByDay($seances ,$exam);
            $exam->setRoom($room);
            
            $this->getDoctrine()->getManager()->flush();

            $id=$teacher->getId();
            return $this->redirectToRoute('teacher_exam_index', [
                'id' => $id,
            ]);
        }

        return $this->render('Front/Teacher/Exam/edit.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
            'teacher' => $teacher ,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("{teacher}/delete/{id}", name="teacher_exam_delete", methods={"DELETE"})
     */
    public function delete(Teacher $teacher , Exam $exam ,Request $request): Response
    {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        

        return $this->redirectToRoute('teacher_exam_index' , [
            'id' => $teacher->getId(),
        ]);
    }

     /**
     * @Route("{teacher}/note/{id}", name="teacher_exam_note", methods={"GET" ,"POST"})
     */
    public function note(Teacher $teacher ,Exam $exam ,ParameterRepository $repoP,Request $request ): Response
    {   
        $param=$repoP->find(1);

        $data["studentExams"]=$exam->getStudentExams() ;
        
        $form = $this->createForm(NoteType::class, $data);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();

            $id=$teacher->getId();
            return $this->redirectToRoute('teacher_exam_index', [
                'id' => $id,
            ]);
        }
        
       
        return $this->render('Front/Teacher/Exam/note.html.twig', [
            'exam' => $exam,
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $repoP->find(1) 
        ]);
    }
    
}
