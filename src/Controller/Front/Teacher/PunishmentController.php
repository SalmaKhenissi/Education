<?php

namespace App\Controller\Front\Teacher;

use App\Entity\Section;
use App\Entity\Teacher;
use App\Entity\Punishment;
use App\Form\PunishmentType;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\ParameterRepository;
use App\Repository\PunishmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/punishments")
 */
class PunishmentController extends AbstractController
{
    /**
     * @Route("/{teacher}/index/{section}", name="teacher_punishment_index", methods={"GET"})
     */
    public function index(Teacher $teacher, Section $section ,PunishmentRepository $PunishmentRepository ,PaginatorInterface $paginator ,ParameterRepository $repoP , Request $request  ): Response
    {   
        $param=$repoP->find(1);
        
        $punishments=$PunishmentRepository->findByTeacher($teacher);
        $students=[];
        foreach($section->getStudents() as $st)
        {
            $students[]=$st->getId();
        } 
        $tabP=[];
        foreach($punishments as $p)
        {   foreach($p->getStudents() as $s)
            {
                if(in_array($s->getId(), $students) )
                {   $k=strtotime($p->getDate()->format('Y-m-d H:i:s'));
                    $tabP[$k]=$p;dump($tabP);
                }
            }
        }
        krsort($tabP);

        $punish=$paginator->paginate($tabP, $request->query->getInt('page', 1), 5);

        return $this->render('Front/Teacher/Punishment/index.html.twig', [
            'punish' => $punish,
            'teacher' => $teacher,
            'section' => $section,
            'parameters' => $param ,
        ]);
    }
    

    /**
     * @Route("/show/{id}", name="teacher_punishment_show", methods={"GET"})
     */
    public function show(Punishment $p ,ParameterRepository $repoP ): Response
    {
       
        return $this->render('Front/Teacher/Punishment/show.html.twig', [
            'punishment' => $p,
            'teacher' => $p->getTeacher(),
            'parameters' => $repoP->find(1) 
        ]);
    }

     /**
     * @Route("{section}/new/{teacher}", name="teacher_punishment_new", methods={"GET","POST"})
     */
    public function new(Section $section ,Teacher $teacher, SeanceRepository $seanceRepository ,Request $request ,ParameterRepository $repoP, SectionRepository $sectionRepository): Response
    {   $param=$repoP->find(1);
        $p = new Punishment();
        $p->setTeacher($teacher);

        $seances=$seanceRepository->findByTeaching($teacher,$section);
        $punish = false ;
        $day = date('D',strtotime(date('Y-m-d'))); 
        if($day == "Mon") {$day = 0 ;}
        else if($day == "Tue") {$day = 1 ;}
        else if($day == "Wed") {$day = 2 ;}
        else if($day == "Thu") {$day = 3 ;}
        else if($day == "Fri") {$day = 4 ;}
        else if($day == "Sat" ){$day = 5 ;}
        foreach($seances as $s)
        { 
            if($s->getDay() == $day)
            {
                $punish = true ;
            }
        }
    
        $form = $this->createForm(PunishmentType::class, $p , [
            'section' => $section
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            if($p->getType() == 1  && $punish == false )
            {
                $this->addFlash('fail' , 'Type de punition n\'est pas validé!');

            }
            else{

                $p->setDate(new \DateTime('now'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($p);
                $entityManager->flush();
                $this->addFlash('success' , 'Ajouté  avec succés!');

                return $this->redirectToRoute('teacher_punishment_index', [
                    'teacher' => $teacher->getId(),
                    'section' => $section->getId()
                ]);
            }
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Punishment/new.html.twig', [
            'p' => $p,
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

     /**
     * @Route("{id}/edit/{section}", name="teacher_punishment_edit", methods={"GET","POST"})
     */
    public function edit(Section $section ,Punishment $p ,Request $request ,ParameterRepository $repoP, SectionRepository $sectionRepository): Response
    {   $param=$repoP->find(1);
    
        $form = $this->createForm(PunishmentType::class, $p , [
            'section' => $section
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($p);
            $entityManager->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('teacher_punishment_index', [
                'teacher' => $p->getTeacher()->getId(),
                'section' => $section->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Punishment/new.html.twig', [
            'p' => $p,
            'teacher' => $p->getTeacher(),
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }
    
    /**
     * @Route("{id}/delete/{section}", name="teacher_punishment_delete", methods={"DELETE"})
     */
    public function delete(Request $request ,Punishment $p , Section $section): Response
    {
            $teacher= $p->getTeacher();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($p);
            $entityManager->flush();
        
            $this->addFlash('success' , 'Supprimé  avec succés!');
        return $this->redirectToRoute('teacher_punishment_index' , [
            'teacher' => $teacher->getId(),
            'section' => $section->getId()
        ]);
    }
}
