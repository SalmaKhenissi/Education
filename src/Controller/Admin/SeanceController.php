<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use App\Entity\Section;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function new(Request $request , Section $section): Response
    {
        $seance = new Seance();
        $choicesDay = Seance::DAY ;
        $seance->setSection($section);
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $day=$seance->getDay();
            $seance->setDay($choicesDay[$day]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();
            //return new Response("heloo w");
            return $this->redirectToRoute('admin_section_timeTable', [
                'id' => $section->getId(),
            ]);
        }

        return $this->render('Admin/Seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_section_timeTable', [
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
