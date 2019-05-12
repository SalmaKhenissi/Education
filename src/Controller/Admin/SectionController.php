<?php
namespace App\Controller\Admin;

use App\Entity\Section;
use App\Entity\Student;
use App\Form\SectionType;
use App\Repository\ExamRepository;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\ParameterRepository;
use App\Repository\SchoolYearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/section")
 */
class SectionController extends AbstractController
{

     /**
     * @Route("/index", name="admin_section_index")
     */
    public function index( SectionRepository $sectionRepository , Request $request ,PaginatorInterface $paginator )
    { 
        $level = $request->get('level');
        $number = $request->get('number');
        $specialty = $request->get('specialty');
        $schoolYear = $request->get('schoolYear');
        $sections=$paginator->paginate($sectionRepository->findByOption($level,$number ,$specialty,$schoolYear), 
                                        $request->query->getInt('page', 1),
                                        12
        );
        return $this->render('Admin/Section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/new", name="admin_section_new", methods={"GET","POST"})
     */
    public function new(Request $request , SectionRepository $sectionRepository , ParameterRepository $parameterRepository , SchoolYearRepository $schoolYearRepository): Response
    {
        $section = new Section();

        $parameter=$parameterRepository->find(1);
        $schoolYear = $schoolYearRepository->findByLibelle($parameter->getSchoolYear());
        
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setSchoolYear($schoolYear[0]);

            $section->setLibelle($sectionRepository->generateName($section) );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();
            return $this->redirectToRoute('admin_section_index');
        }

        return $this->render('Admin/Section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_section_show", methods={"GET"})
     */
    public function show(Section $section, TeacherRepository $repoT  , SeanceRepository $seanceRepository , ExamRepository $examRepository): Response
    {  
        $seances=$seanceRepository->findBySection($section);
        $timetable=$seanceRepository->findTimeTable($seances);

        $exams=$examRepository->findBySection($section);
        $timetableExam=$examRepository->findTimeTable($exams);
        
        $teachers=$repoT->findBySection($seances,$repoT);
         

        return $this->render('Admin/Section/show.html.twig', [
            'section' => $section,
            'timetable' => $timetable ,
            'timetableExam' => $timetableExam ,
            'teachers' =>$teachers
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_section_index', [
                'id' => $section->getId(),
            ]);
        }

        return $this->render('Admin/Section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        
        return $this->redirectToRoute('admin_section_index');
    }

    /**
     * @Route("/affect/{id}", name="admin_section_affect", methods={"GET","POST"})
     */
    public function affect(Section $section, StudentRepository $repoS ,SeanceRepository $seanceRepository ,Request $request ,ParameterRepository $repoP ): Response
    {  $schoolYear=$repoP->find(1)->getSchoolYear();
        $seances=$seanceRepository->findBySection($section);
        $teachers=[];
         for($i=0;$i<count($seances);$i++)
         {
            $teachers[$i]=$seances[$i]->getTeacher();
         }
        $timetable=$seanceRepository->findTimeTable($seances);
        $form = $this->createFormBuilder($section)
                    ->add('students' , EntityType::class , [
                            'class' => 'App\Entity\Student' ,
                          'choices' => $repoS->findByLevel($section,$schoolYear) ,
                          'multiple'=>true ,
                          'label' => ' Liste des éléves'
                         ])
                    
                    ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $students=$section->getStudents(); 
            for($i=0;$i<count($students);$i++)
            {  
                $students[$i]->addSection($section);
                $this->getDoctrine()->getManager()->persist($students[$i]);
            }
            $this->getDoctrine()->getManager()->persist($section);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('Admin/Section/show.html.twig', [
            'section' => $section,
            'timetable' => $timetable,
            'teachers' => $teachers
            ]);
        }
         
        return $this->render('Admin/Section/affect.html.twig', [
                'section' => $section,
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/eliminate/{id}", name="admin_section_eliminate", methods={"GET","POST"})
     */
    public function eliminate(Section $section, StudentRepository $repoS ,SeanceRepository $seanceRepository ,Request $request ,ParameterRepository $repoP ): Response
    {   $schoolYear=$repoP->find(1)->getSchoolYear();

        $seances=$seanceRepository->findBySection($section);
        $teachers=[];
         for($i=0;$i<count($seances);$i++)
         {
            $teachers[$i]=$seances[$i]->getTeacher();
         }
        $timetable=$seanceRepository->findTimeTable($seances);

        $studentsAffected=$section->getStudents();
        $tabA=[]; $i=0;
            for($j=0;$j<=count($studentsAffected);$j++)
            {
                if($studentsAffected[$j]!=null)
                { $tabA[$i]=$studentsAffected[$j]; $i++;}
            }
        
        $form = $this->createFormBuilder($section)
                    ->add('students' , EntityType::class , [
                            'class' => 'App\Entity\Student' ,
                          'choices' => $repoS->findByLevelAffected($section,$schoolYear) ,
                          'multiple'=>true ,
                          'label' => ' Liste des éléves'
                         ])
                    
                    ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $students=$section->getStudents(); 
            $tab=[]; $i=0;
            for($j=0;$j<=count($students);$j++)
            {
                if($students[$j]!=null)
                { $tab[$i]=$students[$j]; $i++;}
            } 

            for($j=0;$j<count($tabA);$j++)
            {   $exist=false; 
                for($i=0;$i<count($tab);$i++)
                {     if($tab[$i]->getId()==$tabA[$j]->getId() )
                        {
                            $exist=true;
                            break;
                        }    
                }
                if($exist==false)
                {   
                    $tabA[$j]->removeSection($section);
                    $this->getDoctrine()->getManager()->persist($tabA[$j]);
                    
                }
            }
            
            
            
            $this->getDoctrine()->getManager()->persist($section);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('Admin/Section/show.html.twig', [
            'section' => $section,
            'timetable' => $timetable,
            'teachers' => $teachers
            ]);
        }
         
        return $this->render('Admin/Section/eliminate.html.twig', [
                'section' => $section,
                'form' => $form->createView(),
        ]);
    }


     /**
     * @Route("/student/{id}", name="admin_section_student", methods={"GET"})
     */
    public function showStudent(Student $student, StudentRepository $studentRepository , ParameterRepository $repoP , SectionRepository $repoS): Response
    {   
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);
        return $this->render('Admin/Section/show_student.html.twig', [
            'student' => $student ,
            'section'=> $section 
            
        ]);
     }
    
}