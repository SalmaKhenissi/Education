<?php

namespace App\Controller\Front\Student;

use App\Entity\Student;
use App\Entity\Document;
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
 * @IsGranted("ROLE_STUDENT" , statusCode=404)
 * @Route("/profile/student/documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/index/{id}", name="student_doc_index", methods={"GET"})
     */
    public function index(Student $student  ,SectionRepository $repoS ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $tab=[];
        foreach($section->getDocuments() as $d)
        {
            if($d->getType() != 2 && $d->getType() != 3 )
            
            {  $k=strtotime($d->getPostedAt()->format('Y-m-d H:i:s'));
                 $tab[$k]=$d;
                
            }
        }
        krsort($tab);

        $docs=$paginator->paginate($tab, $request->query->getInt('page', 1), 5);

        
        return $this->render('Front/Student/Document/index.html.twig', [
            'docs' => $docs,
            'student' => $student,
            'section' => $section ,
            'parameters' => $param ,
        ]);
    }

      

    /**
     * @Route("{student}/show/{doc}", name="student_doc_show", methods={"GET"})
     */
    public function show(Student $student ,Document $doc ,DocumentRepository $documentRepository,SectionRepository $repoS ,ParameterRepository $repoP ): Response
    {
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('Front/Student/Document/show.html.twig', [
            'doc' => $doc,
            'student' => $student,
            'section' => $section ,
            'parameters' => $repoP->find(1) ,
        ]);
    }

   
}
