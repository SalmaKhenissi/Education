<?php

namespace App\Controller\Front\Student;

use App\Entity\Student;
use App\Entity\Document;
use App\Repository\SectionRepository;
use App\Repository\DocumentRepository;
use App\Repository\ParameterRepository;
use App\Repository\ObservationRepository;
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
    public function index(Student $student ,ObservationRepository $observationRepository ,SectionRepository $repoS ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        $tab=[];$nb=0;
        foreach($section->getDocuments() as $d)
        {
            if($d->getType()!='Examen')
            {   $tab[]=$d;
                if($d->getViewed()==0)
                { $nb++;}
            }
        }
        $sorted=[];
        foreach($tab as $d)
        {
            $k=date('n',strtotime($d->getPostedAt()->format('Y-m-d'))).date('d',strtotime($d->getPostedAt()->format('Y-m-d')));
            $sorted[$k]=$d;
        }
        krsort($sorted);

        $docs=$paginator->paginate($sorted, $request->query->getInt('page', 1), 5);

        $nb1=$observationRepository->findNotifications($section);
        return $this->render('Front/Student/Document/index.html.twig', [
            'docs' => $docs,
            'student' => $student,
            'section' => $section ,
            'parameters' => $param ,
            'nb' => $nb,
            'nb1' => $nb1
        ]);
    }

      

    /**
     * @Route("{student}/show/{doc}", name="student_doc_show", methods={"GET"})
     */
    public function show(Student $student ,Document $doc ,ObservationRepository $observationRepository ,DocumentRepository $documentRepository,SectionRepository $repoS ,ParameterRepository $repoP ): Response
    {
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);

        
        $entityManager = $this->getDoctrine()->getManager();
        $doc->setViewed(true);
        $entityManager->flush();

        $nb=$documentRepository->findNotifications($section);
        $nb1=$observationRepository->findNotifications($section);
        return $this->render('Front/Student/Document/show.html.twig', [
            'doc' => $doc,
            'student' => $student,
            'section' => $section ,
            'parameters' => $repoP->find(1) ,
            'nb' => $nb,
            'nb1' => $nb1
        ]);
    }

   
}
