<?php

namespace App\Controller\Front;

use App\Entity\Club;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\Description;
use App\Repository\ClubRepository;
use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use App\Repository\PictureRepository;
use App\Repository\ParameterRepository;
use App\Notification\ContactNotification;
use App\Repository\DescriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function new(Request $request ,ContactNotification  $notification,DescriptionRepository $repoD ,ParameterRepository $repo,PictureRepository $repoPi ,EventRepository $repoE): Response
    {
        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());

        $list= $repoPi->findAll(); $pictures=[];
        foreach($list as $l)
        {
            $k=strtotime($l->getUpdatedAt()->format('Y-m-d H:i:s'));
            $pictures[$k]=$l;
        }

        krsort($pictures);$tabP=[];$i=0;
        foreach($pictures as $p)
        {
            $tabP[]=$p; $i = $i + 1 ;
            if(count($tabP)==7)
            {
                break;
            }
        }

        $listE= $repoE->findAll(); $events=[];
        foreach($listE as $l)
        {
            $k=strtotime($l->getTime()->format('Y-m-d H:i:s'));
            $events[$k]=$l;
        }

        krsort($events);$tabE=[];$i=0;
        foreach($events as $e)
        {
            $tabE[]=$e; $i = $i + 1 ;
            if(count($tabE)==4)
            {
                break;
            }
        }

        $form = $this->createForm(ContactType::class, $contact); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact , $repo , $repoD ,$repoPi , $tabP , $tabE);
            $this->addFlash('success' , 'Votre message a été envoyé  avec succé');
            
        
        }
        return $this->render('Front/Guest/contact.html.twig', [
            'form' => $form->createView() ,
            'parameters' => $repo->find(1)
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function redirectEvents( ParameterRepository $repo ,Request $request ,PaginatorInterface $paginator , EventRepository $repoE)
    { 

        $listE= $repoE->findAll(); $events=[];
        foreach($listE as $l)
        {
            $k=strtotime($l->getTime()->format('Y-m-d H:i:s'));
            $events[$k]=$l;
        }

        krsort($events);

        $event=$paginator->paginate($events , 
                                       $request->query->getInt('page', 1),
                                       6
        );
        return$this->render('Front/Guest/events.html.twig',[
            'parameters' => $repo->find(1),
            'events' => $event ,
            'number' => count($repoE->findAll())
            ]);
    }

    /**
     * @Route("/clubs", name="clubs")
     */
    public function redirectClubs(ParameterRepository $repo , Request $request ,PaginatorInterface $paginator , ClubRepository $repoC)
    { 
        $clubs=$repoC->findAll();
        $club=$paginator->paginate($clubs , 
                                       $request->query->getInt('page', 1),
                                       2
        );

        return$this->render('Front/Guest/clubs.html.twig',[
            'parameters' => $repo->find(1) ,
            'clubs' => $club ,
            'number' => count($repoC->findAll())
            ]);
    }

    /**
     * @Route("/club/{id}", name="club")
     */
    public function redirectClub(ParameterRepository $repo  , Club $club)
    { 
        return$this->render('Front/Guest/club.html.twig',[
            'parameters' => $repo->find(1) ,
            'club' => $club 
            ]);
    }
    /**
     * @Route("/bilinguisme", name="bilinguisme")
     */
    public function redirectBi(ParameterRepository $repo , DescriptionRepository $repoD)
    { 
        $service=$repoD->findOneById(2);
        return$this->render('Front/Guest/bilinguisme.html.twig',[
            'parameters' => $repo->find(1) ,
            'service' => $service 
            ]);
    }

    /**
     * @Route("/help", name="help")
     */
    public function redirectHelp(ParameterRepository $repo , DescriptionRepository $repoD )
    { 
        $service=$repoD->findOneById(3);
        return$this->render('Front/Guest/help.html.twig',[
            'parameters' => $repo->find(1) ,
            'service' => $service 
            ]);
    }
    /**
     * @Route("/health", name="health")
     */
    public function redirectHealth(ParameterRepository $repo , DescriptionRepository $repoD)
    { 
        $service=$repoD->findOneById(4);
        return$this->render('Front/Guest/health.html.twig',[
            'parameters' => $repo->find(1) ,
            'service' => $service 
            ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function redirectAbout( ParameterRepository $repo ,DescriptionRepository $repoD)
    { 
        return$this->render('Front/Guest/about.html.twig',[
            'parameters' => $repo->find(1) ,
            'desc1' => $repoD->find(5),
            'desc2' => $repoD->find(6),
            'desc3' => $repoD->find(7),
            'desc4' => $repoD->find(8),
            'desc5' => $repoD->find(9),
            'desc6' => $repoD->find(10),
            ]);
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function redirectGallery( ParameterRepository $repo, Request $request ,PaginatorInterface $paginator , PictureRepository $repoP)
    { 

        $list= $repoP->findAll(); $pictures=[];
        foreach($list as $l)
        {
            $k=strtotime($l->getUpdatedAt()->format('Y-m-d H:i:s'));
            $pictures[$k]=$l;
        }

        krsort($pictures);

        $picture=$paginator->paginate($pictures , 
                                       $request->query->getInt('page', 1),
                                       11
        );
        return$this->render('Front/Guest/gallery.html.twig' ,[
            'parameters' => $repo->find(1) ,
            'pictures' => $picture ,
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