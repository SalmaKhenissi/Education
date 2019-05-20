<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use App\Entity\Section;
use App\Form\SeanceType;
use App\Form\SeanceType2;
use App\Repository\RoomRepository;
use App\Repository\CourseRepository;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\TeacherRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/seance")
 */
class SeanceController extends AbstractController
{
     
    

    /**
     * @Route("/new/{id}", name="admin_seance_new", methods={"GET","POST"})
     */
    public function new(Request $request , Section $section ,CourseRepository  $courseRepository  ): Response
    {
        $seance = new Seance();
        $choicesDay = Seance::DAY ;
        $seance->setSection($section);
        
        $form = $this->createForm(SeanceType::class, $seance , [
            'section' => $section,
            'courseRepository' => $courseRepository
        ]);
        
    
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $day=$seance->getDay();
            $seance->setDay($choicesDay[$day]);

            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_seance_new2', [
                'id' => $seance->getId(),
            ]);
        }

        return $this->render('Admin/Seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/new2/{id}", name="admin_seance_new2", methods={"GET","POST"})
     */
    public function new2(Request $request ,  Seance $seance , TeacherRepository $teacherRepository , RoomRepository $roomRepository ,SeanceRepository $seanceRepository): Response
    {
        $form = $this->createForm(SeanceType2::class, $seance , [
            'seance' => $seance,
            'roomRepository' => $roomRepository,
            'teacherRepository' => $teacherRepository ,
            'seanceRepository' => $seanceRepository
        ]);
        
        
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('admin_section_show', [
                'id' => $seance->getsection()->getId(),
            ]);
        }
        else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->render('Admin/Seance/new_2.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }
    

    
    /**
     * @Route("/{id}/edit", name="admin_seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance , CourseRepository  $courseRepository): Response
    {   
        $choicesDay = Seance::DAY ;
        $form = $this->createForm(SeanceType::class, $seance , [
            'section' => $seance->getsection(),
            'courseRepository' => $courseRepository
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $day=$seance->getDay();
            $seance->setDay($choicesDay[$day]);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_seance_edit2', [
                'id' => $seance->getId(),
            ]);
        }

        return $this->render('Admin/Seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit2", name="admin_seance_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Seance $seance , TeacherRepository $teacherRepository , RoomRepository $roomRepository ,SeanceRepository $seanceRepository ): Response
    {   
        $form = $this->createForm(SeanceType2::class, $seance , [
            'seance' => $seance,
            'roomRepository' => $roomRepository,
            'teacherRepository' => $teacherRepository ,
            'seanceRepository' => $seanceRepository
        ]);
        dump($seanceRepository->findAllByDay($seance->getDay() ,$seance->getSection()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_seance_index', [
                'id' => $seance->getSection()->getId(),
                'day' => $seance->getDay()
            ]);
        }
       /* else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }*/

        return $this->render('Admin/Seance/edit_2.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{day}", name="admin_seance_index", methods={"GET"})
     */
    public function index($id,$day,SeanceRepository $repoS , Request $request , SectionRepository $repoSection): Response
    {   
        $seances=$repoS->findByDay($day,$id);
        return $this->render('Admin/Seance/index.html.twig', [
            'seances' => $seances,
            'day' => $day ,
            'id' => $id
        ]);
    }


    /**
     * @Route("/{id}", name="admin_seance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seance $seance ,SeanceRepository $seanceRepository ) : Response
    {
        
       
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        
        return $this->redirectToRoute('admin_section_show', [
            'id' => $seance->getSection()->getId(),
        ]);
    }

   
}
