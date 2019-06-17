<?php
namespace App\Controller\Front\Guardian;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Student;
use App\Entity\Guardian;
use App\Repository\SeanceRepository;

use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\ParameterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian")
 */
class RedirectionController extends AbstractController
{


    

     /**
     * @Route("/teachers/{id}", name="guardian_teachers")
     */
    public function redirectTeachers( Guardian $guardian ,Request $request ,ParameterRepository $repoP ,PaginatorInterface $paginator ,StudentRepository $repoS , SectionRepository $repoSec , TeacherRepository $repoT)
    { 
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $children=$repoS->findByGuardian($guardian,$schoolYear);
        $TabSea=[]; $seances=[]; $sections=[];
        foreach($children as $c)
        { 
            foreach($c->getSections() as $s)
            {  
                if($s->getSchoolYear()->getLibelle()==$schoolYear) 
                {  
                    $sections[]=$s;

                    $courses=[];
                    foreach($s->getSeances() as $sea)
                    {   $course=$sea->getCourse()->getLibelle().$s;
                        if(!in_array($course ,$courses  ))
                        {   $courses[]=$course;
                            $seances[$course.$s]=$sea;
                        }
                    }

                }
            }
            ksort($seances);
        }
      
        $seances=$paginator->paginate($seances, $request->query->getInt('page', 1),10 );
        
        
        return $this->render('Front/Guardian/teachers.html.twig',[
            'guardian' => $guardian ,
            'parameters' => $repoP->find(1) ,
            'seances' =>$seances ,
            'sections' => $sections
            ]);
    }

    
    /**
     * @Route("/timetables/{id}", name="guardian_timetable" , methods={"GET"})
     */
    public function redirectTimetable(Guardian $guardian ,StudentRepository $studentRepository ,ParameterRepository $repoP ,SeanceRepository $seanceRepository ,SectionRepository $repoS )
    {   

        $schoolYear=$repoP->find(1)->getSchoolYear();
        $children=$studentRepository->findByGuardian($guardian,$schoolYear);
        $tab=[];
        foreach($children as $c)
        {
            $sections=$c->getSections();
            $section =$repoS->findByYear($sections,$schoolYear);

            $seances=$seanceRepository->findBySection($section);
            $timetable=$seanceRepository->findTimeTable($seances);
            $tab[]=[$c,$timetable];
        }


        return$this->render('Front/Guardian/timetable.html.twig' , [
            'parameters' => $repoP->find(1) ,
            'tabC' => $tab,
            'guardian' => $guardian
            ]);
    }

     /**
     * @Route("/{id}/timetable2", name="guardian_student_timetable2" , methods={"GET"} )
     */
    public function timetable(Student $student,ParameterRepository $repoP  , SeanceRepository $seanceRepository ,SectionRepository $repoS )
    {

        //configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont' , 'Arial');

        //instantiate Dompdf
        $dompdf = new Dompdf($pdfOptions);
        
        $schoolYear=$repoP->find(1)->getSchoolYear();
        $sections=$student->getSections();
        $section =$repoS->findByYear($sections,$schoolYear);


        $seances=$seanceRepository->findBySection($section);
         $timetable=$seanceRepository->findTimeTable($seances);

        //Retrieve the HTML generated in our twig
        $html= $this->renderView('Front/Guardian/timetable2.html.twig', [
            'section' => $section,
            'timetable' => $timetable ,
            ]);

        //load HTML to DOMPpdf
        $dompdf->loadHtml($html);

        //options:Setup the paper size
        $dompdf->setPaper('A3' , 'portrait');

        //Render the html as PDF
        $dompdf->render();

        //ouput the generated PDF to browser
        $dompdf->stream("timetable.pdf",[
            "Attachment" => true
        ]);




        
       return $this->render();
    }
    
   

    
}