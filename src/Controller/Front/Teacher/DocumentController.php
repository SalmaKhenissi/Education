<?php

namespace App\Controller\Front\Teacher;

use App\Entity\Teacher;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\SectionRepository;
use App\Repository\DocumentRepository;
use App\Repository\ParameterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="teacher_doc_index", methods={"GET"})
     */
    public function index(Teacher $teacher ,DocumentRepository $documentRepository ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $tab=[];
        foreach($teacher->getDocuments() as $d){
            foreach($d->getSections() as $s)
            { if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $k=strtotime($d->getPostedAt()->format('Y-m-d H:i:s'));
                    $tab[$k]=$d;
                }
            }
        }
        krsort($tab);

        $docs=$paginator->paginate($tab, $request->query->getInt('page', 1), 5);
        return $this->render('Front/Teacher/Document/index.html.twig', [
            'docs' => $docs,
            'teacher' => $teacher,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/new/{id}", name="teacher_doc_new", methods={"GET","POST"})
     */
    public function new(Teacher $teacher ,Request $request ,ParameterRepository $repoP, SectionRepository $sectionRepository): Response
    {   $param=$repoP->find(1);
        $doc = new Document();
        $doc->setTeacher($teacher);
        $sections=[];
        foreach($sectionRepository->findByTeacher($teacher) as $s)
            {
                if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $sections[]=$s; }
            }
    
        $form = $this->createForm(DocumentType::class, $doc, [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $doc->setPostedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doc);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('teacher_doc_index', [
                'id' => $teacher->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Document/new.html.twig', [
            'doc' => $doc,
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/show/{id}", name="teacher_doc_show", methods={"GET"})
     */
    public function show(Document $doc ,ParameterRepository $repoP ): Response
    {
       
        return $this->render('Front/Teacher/Document/show.html.twig', [
            'doc' => $doc,
            'teacher' => $doc->getTeacher(),
            'parameters' => $repoP->find(1) 
        ]);
    }

    /**
     * @Route("/{id}/edit", name="teacher_doc_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $doc ,ParameterRepository $repoP , SectionRepository $sectionRepository): Response
    {  $param=$repoP->find(1);
        $sections=[];
        $teacher=$doc->getTeacher();
        foreach($sectionRepository->findByTeacher($teacher) as $s)
            {
                if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
                { $sections[]=$s; }
            }
        $form = $this->createForm(DocumentType::class, $doc , [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);
        $docName=$doc->getDocName();

        if ($form->isSubmitted() && $form->isValid()) {

            if($doc->getDocFile()!=null)
            {
                unlink(getcwd().'\uploads\files\\'.$doc->getDocName());
                $doc->setDocName(null);
            }
            $doc->setPostedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('teacher_doc_index', [
                'id' =>$doc->getTeacher()->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Teacher/Document/edit.html.twig', [
            'doc' => $doc,
            'form' => $form->createView(),
            'teacher' => $doc->getTeacher() ,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="teacher_doc_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Document $doc): Response
    {
            $teacher= $doc->getTeacher();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($doc);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');

        return $this->redirectToRoute('teacher_doc_index' , [
            'id' => $teacher->getId(),
        ]);
    }
}
