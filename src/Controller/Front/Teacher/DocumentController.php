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

        $tab=$teacher->getDocuments();
        $sorted=[];
        foreach($tab as $d)
        {
            $k=date('n',strtotime($d->getPostedAt()->format('Y-m-d'))).date('d',strtotime($d->getPostedAt()->format('Y-m-d')));
            $sorted[$k]=$d;
        }
        krsort($sorted);

        $docs=$paginator->paginate($sorted, $request->query->getInt('page', 1), 5);
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
        $choicesType = Document::TYPE ;
        $doc = new Document();
        $doc->setTeacher($teacher);
        $sections=$sectionRepository->findByTeacher($teacher);
        $form = $this->createForm(DocumentType::class, $doc, [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type=$doc->getType();
            $doc->setType($choicesType[$type]);

            $doc->setPostedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doc);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_doc_index', [
                'id' => $teacher->getId(),
            ]);
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
        $choicesType = Document::TYPE ;
        $sections=$sectionRepository->findByTeacher($doc->getTeacher());
        $form = $this->createForm(DocumentType::class, $doc , [
            'sections' => $sections  
        ]);
        $form->handleRequest($request);
        $docName=$doc->getDocName();

        if ($form->isSubmitted() && $form->isValid()) {
            $type=$doc->getType();
            $doc->setType($choicesType[$type]);

            if($doc->getDocFile()!=null)
            {
                unlink(getcwd().'\uploads\files\\'.$doc->getDocName());
                $doc->setDocName(null);
            }
            $doc->setPostedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teacher_doc_index', [
                'id' =>$doc->getTeacher()->getId()
            ]);
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
        

        return $this->redirectToRoute('teacher_doc_index' , [
            'id' => $teacher->getId(),
        ]);
    }
}
