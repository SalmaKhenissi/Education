<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="admin_event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('Admin/Event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('admin_event_index');
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('Admin/Event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('admin_event_index', [
                'id' => $event->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');

        return $this->redirectToRoute('admin_event_index');
    }
}
