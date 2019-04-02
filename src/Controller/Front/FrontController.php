<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ClubRepository;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\ParameterRepository;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/front")
 */
class FrontController extends AbstractController
{

    /**
     * @Route("/contact", name="contact_new", methods={"GET","POST"})
     */
    public function new(Request $request ,ContactNotification  $notification ,ParameterRepository $repo): Response
    {
        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());

        $form = $this->createForm(ContactType::class, $contact); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success' , 'Votre message a été envoyé  avec succé');
            return $this->redirectToRoute('contact_new');
        }
        return $this->render('Front/Guest/contact.html.twig', [
            'form' => $form->createView() ,
            'parameters' => $repo->find(1)
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function redirectEvents( ParameterRepository $repo , EventRepository $repoE)
    { 
        return$this->render('Front/Guest/events.html.twig',[
            'parameters' => $repo->find(1),
            'events' => $repoE->findAll() ,
            'number' => count($repoE->findAll())
            ]);
    }

    /**
     * @Route("/clubs", name="clubs")
     */
    public function redirectClubs(ParameterRepository $repo  , ClubRepository $repoC)
    { 
        return$this->render('Front/Guest/clubs.html.twig',[
            'parameters' => $repo->find(1) ,
            'clubs' => $repoC->findAll() ,
            'number' => count($repoC->findAll())
            ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function redirectAbout( ParameterRepository $repo)
    { 
        return$this->render('Front/Guest/about.html.twig',[
            'parameters' => $repo->find(1)
            ]);
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function redirectGallery( ParameterRepository $repo , PictureRepository $repoP)
    { 
        return$this->render('Front/Guest/gallery.html.twig' ,[
            'parameters' => $repo->find(1) ,
            'pictures' => $repoP->findAll() ,
            'number' => count($repoP->findAll())

        ]);
    }

    /**
     * @Route("/teaching", name="teaching")
     */
    public function redirectTeaching( ParameterRepository $repo)
    { 
        return$this->render('Front/Guest/teaching.html.twig' ,[
            'parameters' => $repo->find(1)
            ]);
    }
}