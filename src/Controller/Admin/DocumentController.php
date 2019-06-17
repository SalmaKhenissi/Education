<?php

namespace App\Controller\Admin;


use App\Entity\Document;
use App\Repository\SectionRepository;
use App\Repository\DocumentRepository;
use App\Repository\ParameterRepository;
use App\Repository\SchoolYearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/index", name="admin_doc_index", methods={"GET"})
     */
    public function index( SchoolYearRepository $repoSY ,DocumentRepository $repoD ,ParameterRepository $repoP , Request $request ,PaginatorInterface $paginator): Response
    {   $param=$repoP->find(1);

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $SY =$repoSY->findOneByLibelle($schoolYear);
        $start=$SY->getStartAt()->format('Y-m-d');
        $finish=$SY->getFinishAt()->format('Y-m-d');
        $documents=$repoD->findAll();


        $tab=[];
        foreach($documents as $d)
        {   $doc=$d->getPostedAt()->format('Y-m-d');
            if($doc >= $start && $doc <= $finish)
            {
                if($d->getType() == 2 || $d->getType() == 3 )
                
                {  $k=strtotime($d->getPostedAt()->format('Y-m-d H:i:s'));
                    $tab[$k]=$d;
                    
                }
            }
        }
        krsort($tab);

        $docs=$paginator->paginate($tab, $request->query->getInt('page', 1), 7);

        
        return $this->render('Admin/Document/index.html.twig', [
            'docs' => $docs,
            'parameters' => $param 
        ]);
    }


    
    /**
     * @Route("/show/{doc}", name="admin_doc_show", methods={"GET"})
     */
    public function show(Document $doc ,DocumentRepository $documentRepository ,ParameterRepository $repoP ): Response
    {
      

        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('Admin/Document/show.html.twig', [
            'doc' => $doc,
            'parameters' => $repoP->find(1) ,
        ]);
    }

      
   
}
