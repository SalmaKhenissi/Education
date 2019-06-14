<?php

namespace App\Controller\Admin;

use App\Entity\Quarter;
use App\Entity\SchoolYear;
use App\Form\SchoolYearType;
use App\Repository\SchoolYearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
                                      5
       );
        return $this->render('Admin/School_year/index.html.twig', [
            'school_years' => $schoolYears,
        ]);
    }

    /**
     * @Route("/new", name="admin_school_year_new", methods={"GET","POST"})
     */
    public function new(Request $request ): Response
    {
        $schoolYear = new SchoolYear();
        $quarter1 = new Quarter();$quarter1->setNumber(1);
        $quarter2 = new Quarter();$quarter2->setNumber(2);
        $quarter3 = new Quarter();$quarter3->setNumber(3);
        $schoolYear->addQuarter($quarter1);
        $schoolYear->addQuarter($quarter2);
        $schoolYear->addQuarter($quarter3);

        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $start=date('Y',strtotime($schoolYear->getStartAt()->format('Y-m-d')));
            $finish=date('Y',strtotime($schoolYear->getFinishAt()->format('Y-m-d')));
            $schoolYear->setLibelle($start.'/'.$finish);

            foreach( $schoolYear->getQuarters() as $q)
             {
                $number=$q->getNumber();
                $q->setLibelle('Trimestre '.$number);

                if($number==1)
                {
                    $q->setCoefficient(1);
                }
                else
                {
                    $q->setCoefficient(2);
                }

             }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schoolYear);
            $entityManager->flush();

            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('admin_school_year_index');
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
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
            'schoolYear' => $schoolYear,
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

            $start=date('Y',strtotime($schoolYear->getStartAt()->format('Y-m-d')));
            $finish=date('Y',strtotime($schoolYear->getFinishAt()->format('Y-m-d')));
            $schoolYear->setLibelle($start.'/'.$finish);

           

            foreach( $schoolYear->getQuarters() as $q)
             {
                $number=$q->getNumber();
                $q->setLibelle('Trimestre'.$number);
                if($number==1)
                {
                    $q->setCoefficient(1);
                }
                else
                {
                    $q->setCoefficient(2);
                }

             }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('admin_school_year_index', [
                'id' => $schoolYear->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
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
            $this->addFlash('success' , 'Supprimé  avec succés!');

        return $this->redirectToRoute('admin_school_year_index');
    }
}
