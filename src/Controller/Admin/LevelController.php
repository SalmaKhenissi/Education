<?php

namespace App\Controller\Admin;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/level")
 */
class LevelController extends AbstractController
{
    /**
     * @Route("/", name="admin_level_index", methods={"GET"})
     */
    public function index(LevelRepository $levelRepository ,PaginatorInterface $paginator , Request $request): Response
    {
        $levels=$paginator->paginate( $levelRepository->findAll(),
                                      $request->query->getInt('page', 1),
                                      10
       );
        return $this->render('Admin/Level/index.html.twig', [
            'levels' => $levels,
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_level_show", methods={"GET"})
     */
    public function show(Level $level, LevelRepository $levelRepository): Response
    {  
        return $this->render('Admin/Level/show.html.twig', [
            'level' => $level  
            
        ]);
    }

    /**
     * @Route("/new", name="admin_level_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($level);
            $entityManager->flush();

            return $this->redirectToRoute('admin_level_index');
        }

        return $this->render('Admin/Level/new.html.twig', [
            'level' => $level,
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("/{id}/edit", name="admin_level_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Level $level): Response
    {
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_level_index', [
                'id' => $level->getId(),
            ]);
        }

        return $this->render('Admin/Level/edit.html.twig', [
            'level' => $level,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_level_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Level $level): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($level);
            $entityManager->flush();
        

        return $this->redirectToRoute('admin_level_index');
    }
}
