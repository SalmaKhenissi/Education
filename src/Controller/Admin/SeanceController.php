<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use App\Entity\Section;
use App\Form\SeanceType;
use App\Repository\CourseRepository;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
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
    public function new(Request $request , Section $section ,CourseRepository  $courseRepository): Response
    {
        $seance = new Seance();
        $choicesDay = Seance::DAY ;
        $seance->setSection($section);
        $seanceType = new SeanceType($section);
        $form = $this->createFormBuilder($seance)
                    ->add('day' , ChoiceType::class , [
                          'choices' => $this->getDayChoices() ,
                          'multiple'=>false ,
                          'label' => ' Jour'
                         ])
                    ->add('startAt' , TimeType::class, [
                          'label' => 'Début',
                         'widget' => 'single_text' 
                         ])
                    ->add('finishAt' , TimeType::class, [
                          'label' => 'Fin',
                          'widget' => 'single_text'
                        ])
                    ->add('room'  , EntityType::class ,[
                          'class' => 'App\Entity\Room' ,
                          'choice_label' => 'number',
                          'multiple' => false ,
                          'label' => ' Salle'
                         ])
                    ->add('teacher' , EntityType::class , [
                          'class' => 'App\Entity\Teacher' ,
                          'multiple' => false ,
                          'label' => 'Enseignant',
                        ])
                    ->add('course' , EntityType::class , [
                          'class' => 'App\Entity\Course' ,
                          'choices' => $courseRepository->findByLevel($section->getLevel()),
                          'multiple'=>false ,
                          'label' => ' Cours' 
                        ])
                    ->getForm();
    
      
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $day=$seance->getDay();
            $seance->setDay($choicesDay[$day]);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();
            //return new Response("heloo w");
            return $this->redirectToRoute('admin_section_show', [
                'id' => $section->getId(),
            ]);
        }

        return $this->render('Admin/Seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }
    private function getDayChoices()
    {
        $choices = Seance::DAY ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }

    

    /**
     * @Route("/{id}/edit", name="admin_seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance , CourseRepository  $courseRepository): Response
    {   $choicesDay = Seance::DAY ;
        $form = $this->createFormBuilder($seance)
                    ->add('day' , ChoiceType::class , [
                          'choices' => $this->getDayChoices() ,
                          'multiple'=>false ,
                          'label' => ' Jour'
                         ])
                    ->add('startAt' , TimeType::class, [
                          'label' => 'Début',
                         'widget' => 'single_text' 
                         ])
                    ->add('finishAt' , TimeType::class, [
                          'label' => 'Fin',
                          'widget' => 'single_text'
                        ])
                    ->add('room'  , EntityType::class ,[
                          'class' => 'App\Entity\Room' ,
                          'choice_label' => 'number',
                          'multiple' => false ,
                          'label' => ' Salle'
                         ])
                    ->add('teacher' , EntityType::class , [
                          'class' => 'App\Entity\Teacher' ,
                          'multiple' => false ,
                          'label' => 'Enseignant',
                        ])
                    ->add('course' , EntityType::class , [
                          'class' => 'App\Entity\Course' ,
                          'choices' => $courseRepository->findByLevel($section->getLevel()),
                          'multiple'=>false ,
                          'label' => ' Cours' 
                        ])
                    ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_section_show', [
                'id' => $seance->getSection(),
            ]);
        }

        return $this->render('Admin/Seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_seance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seance $seance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_section_timeTable', [
            'id' => $seance->getSection(),
        ]);
    }

   
}
