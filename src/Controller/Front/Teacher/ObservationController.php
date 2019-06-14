<?php

namespace App\Controller\Front\Teacher;

use App\Entity\Teacher;
use App\Entity\Observation;
use App\Form\ObservationType;
use App\Repository\SectionRepository;
use App\Repository\DocumentRepository;
use App\Repository\ParameterRepository;
use App\Repository\ObservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/observation")
 */
class ObservationController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="teacher_obs_index", methods={"GET"})
     */
    public function index(Teacher $teacher ,ObservationRepository $observationRepository ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $tab=[];
        foreach($teacher->getObservations() as $o){
            foreach($o->getSections() as $s)
            { if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $k=strtotime($o->getPostedAt()->format('Y-m-d H:i:s'));
                    $tab[$k]=$o;
                }
            }
        }
        krsort($tab);

        $obs=$paginator->paginate($tab, $request->query->getInt('page', 1), 5);
        return $this->render('Front/Teacher/Observation/index.html.twig', [
            'obs' => $obs,
            'teacher' => $teacher,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/new/{id}", name="teacher_obs_new", methods={"GET","POST"})
     */
    public function new(Teacher $teacher ,Request $request ,ParameterRepository $repoP, SectionRepository $sectionRepository): Response
    {   $param=$repoP->find(1);
        $obs = new Observation();
        $obs->setTeacher($teacher);
        $sections=[];
        foreach($sectionRepository->findByTeacher($teacher) as $s)
            {
                if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $sections[]=$s; }
            }
    
        $form = $this->createForm(ObservationType::class, $obs, [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $obs->setPostedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($obs);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('teacher_obs_index', [
                'id' => $teacher->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Observation/new.html.twig', [
            'obs' => $obs,
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

    

    /**
     * @Route("/{id}/edit", name="teacher_obs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Observation $obs ,ParameterRepository $repoP , SectionRepository $sectionRepository): Response
    {  $param=$repoP->find(1);
        $sections=[];
        $teacher=$obs->getTeacher();
        foreach($sectionRepository->findByTeacher($teacher) as $s)
            {
                if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $sections[]=$s; }
            }
        $form = $this->createForm(ObservationType::class, $obs , [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $obs->setPostedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('teacher_obs_index', [
                'id' =>$obs->getTeacher()->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Observation/edit.html.twig', [
            'obs' => $obs,
            'form' => $form->createView(),
            'teacher' => $obs->getTeacher() ,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="teacher_obs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Observation $obs): Response
    {
            $teacher= $obs->getTeacher();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($obs);
            $entityManager->flush();
        
            $this->addFlash('success' , 'Supprimé  avec succés!');
        return $this->redirectToRoute('teacher_obs_index' , [
            'id' => $teacher->getId(),
        ]);
    }
}
