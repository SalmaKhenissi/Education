<?php

namespace App\Controller\Admin;

use App\Entity\Specialty;
use App\Form\SpecialtyType;
use App\Repository\SpecialtyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/specialty")
 */
class SpecialtyController extends AbstractController
{
    /**
     * @Route("/", name="admin_specialty_index", methods={"GET"})
     */
    public function index(SpecialtyRepository $specialtyRepository ,PaginatorInterface $paginator , Request $request): Response
    {
        $specialties=$paginator->paginate( $specialtyRepository->findAll(),
                                      $request->query->getInt('page', 1),
                                      10
       );
        return $this->render('Admin/Specialty/index.html.twig', [
            'specialties' => $specialties,
        ]);
    }

     /**
     * @Route("/show/{id}", name="admin_specialty_show", methods={"GET"})
     */
    public function show(Specialty $specialty, SpecialtyRepository $specialtyRepository): Response
    {  
        return $this->render('Admin/Specialty/show.html.twig', [
            'specialty' => $specialty  
            
        ]);
    }

    /**
     * @Route("/new", name="admin_specialty_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $specialty = new Specialty();
        $form = $this->createForm(SpecialtyType::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specialty);
            $entityManager->flush();

            return $this->redirectToRoute('admin_specialty_index');
        }

        return $this->render('Admin/Specialty/new.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="admin_specialty_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Specialty $specialty): Response
    {
        $form = $this->createForm(SpecialtyType::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_specialty_index', [
                'id' => $specialty->getId(),
            ]);
        }

        return $this->render('Admin/Specialty/edit.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_specialty_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Specialty $specialty): Response
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($specialty);
            $entityManager->flush();
        

        return $this->redirectToRoute('admin_specialty_index');
    }
}
