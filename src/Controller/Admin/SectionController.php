<?php
namespace App\Controller\Admin;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/section")
 */
class SectionController extends AbstractController
{

     /**
     * @Route("/index", name="admin_section_index")
     */
    public function index( SectionRepository $sectionRepository , Request $request ,PaginatorInterface $paginator )
    { 
        $level = $request->get('level');
        $track = $request->get('track');
        $nbrGroup = $request->get('nbrGroup');
        $sections=$paginator->paginate($sectionRepository->findByOption($level,$track,$nbrGroup), 
                                        $request->query->getInt('page', 1),
                                        12
        );
        return $this->render('Admin/Section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/new", name="admin_section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $section = new Section();
        $choicesLevel = Section::LEVEL ;
        $choicesTrack = Section::TRACK ;

        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $level=$section->getLevel();
            $section->setLevel($choicesLevel[$level]);

            $track=$section->getTrack();
            $section->setTrack($choicesTrack[$track]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();
            return $this->redirectToRoute('admin_section_index');
        }

        return $this->render('Admin/Section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_section_show", methods={"GET"})
     */
    public function show(Section $section, StudentRepository $studentRepository  , TeacherRepository $teacherRepository , SeanceRepository $seanceRepository): Response
    {   
        
        $nbrS=$studentRepository->countBySection($section->getId());
        return $this->render('Admin/Section/show.html.twig', [
            'section' => $section,
            'seances' => $seanceRepository->findBySection($section),
            'students' => $nbrS ,
            'teachers' => count($teacherRepository->countBySection($section->getId()))
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_section_index', [
                'id' => $section->getId(),
            ]);
        }

        return $this->render('Admin/Section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_section_index');
    }

    
}