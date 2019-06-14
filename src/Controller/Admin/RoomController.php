<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="admin_room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository,PaginatorInterface $paginator , Request $request): Response
    {
        $rooms=$paginator->paginate( $roomRepository->findAll(),
                                      $request->query->getInt('page', 1),
                                      8
       );
        return $this->render('Admin/Room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/new", name="admin_room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('admin_room_index');
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('Admin/Room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('admin_room_index', [
                'id' => $room->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');

        return $this->redirectToRoute('admin_room_index');
    }
}
