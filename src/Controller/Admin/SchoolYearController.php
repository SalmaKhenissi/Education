<?php

namespace App\Controller\Admin;

use App\Entity\SchoolYear;
use App\Form\SchoolYearType;
use App\Repository\SchoolYearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/school/year")
 */
class SchoolYearController extends AbstractController
{
    /**
     * @Route("/", name="admin_school_year_index", methods={"GET"})
     */
    public function index(SchoolYearRepository $schoolYearRepository,PaginatorInterface $paginator , Request $request): Response
    {
        $schoolYears=$paginator->paginate( $schoolYearRepository->findAll(),
                                      $request->query->getInt('page', 1),
                                      8
       );
        return $this->render('Admin/School_year/index.html.twig', [
            'school_years' => $schoolYears,
        ]);
    }

    /**
     * @Route("/new", name="admin_school_year_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $schoolYear = new SchoolYear();
        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schoolYear);
            $entityManager->flush();

            return $this->redirectToRoute('admin_school_year_index');
        }

        return $this->render('Admin/School_year/new.html.twig', [
            'school_year' => $schoolYear,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_school_year_show", methods={"GET"})
     */
    public function show(SchoolYear $schoolYear): Response
    {
        return $this->render('Admin/School_year/show.html.twig', [
            'school_year' => $schoolYear,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_school_year_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SchoolYear $schoolYear): Response
    {
        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_school_year_index', [
                'id' => $schoolYear->getId(),
            ]);
        }

        return $this->render('Admin/School_year/edit.html.twig', [
            'school_year' => $schoolYear,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_school_year_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SchoolYear $schoolYear): Response
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($schoolYear);
            $entityManager->flush();
        

        return $this->redirectToRoute('admin_school_year_index');
    }
}
