<?php
namespace App\Controller\Front\Teacher;

use App\Entity\Section;
use App\Entity\Teacher;
use App\Entity\Discipline;
use App\Form\RegisterType;
use App\Form\DisciplineType;
use App\Repository\SeanceRepository;
use App\Repository\SectionRepository;
use App\Repository\StudentRepository;
use App\Repository\ParameterRepository;
use App\Repository\DisciplineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_TEACHER" , statusCode=404)
 * @Route("/profile/teacher/Discipline")
 */
class DisciplineController extends AbstractController
{

    /**
     * @Route("/sections/{id}", name="teacher_discipline_sections", methods={"GET"})
     */
    public function section(Teacher $teacher  ,SectionRepository $sectionRepository ,ParameterRepository $repoP ): Response
    {   
        $param=$repoP->find(1);
        $sections=$sectionRepository->findbyTeacher($teacher);
        foreach($sections as $s){
            if($s->getSchoolYear()->getLibelle()==$param->getSchoolYear())
            {
                $tab[]=$s;
            }
        }
        
        return $this->render('Front/Teacher/Discipline/sections.html.twig', [
            'sections' => $tab,
            'teacher' => $teacher,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/{teacher}/index/{section}", name="teacher_discipline_index", methods={"GET"})
     */
    public function index(Teacher $teacher, Section $section , SeanceRepository $seanceRepository ,DisciplineRepository $disciplineRepository ,ParameterRepository $repoP , Request $request  ): Response
    {   
        $param=$repoP->find(1);
        
        $seances=$seanceRepository->findByTeaching($teacher,$section);
        $call = false ;
        $day = date('D',strtotime(date('Y-m-d'))); 
        if($day == "Mon") {$day = 0 ;}
        else if($day == "Tue") {$day = 1 ;}
        else if($day == "Wed") {$day = 2 ;}
        else if($day == "Thu") {$day = 3 ;}
        else if($day == "Fri") {$day = 4 ;}
        else if($day == "Sat" ){$day = 5 ;}
        foreach($seances as $s)
        { 
            if($s->getDay() == $day)
            {
                $call = true ;
            }
        }
        
        
        $register=$disciplineRepository->findbySeances($seances );

        return $this->render('Front/Teacher/Discipline/index.html.twig', [
            'register' => $register,
            'teacher' => $teacher,
            "call" => $call ,
            'section' => $section,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("/{teacher}/show/{section}/{date}", name="teacher_discipline_show", methods={"GET"})
     */
    public function show(Teacher $teacher, Section $section , $date , SeanceRepository $seanceRepository ,DisciplineRepository $disciplineRepository ,ParameterRepository $repoP , Request $request  ): Response
    {   
        $param=$repoP->find(1);
        
        $seances=$seanceRepository->findByTeaching($teacher,$section);
        
        $register=$disciplineRepository->findbySeances($seances );

        $page=$disciplineRepository->findByDate($register,$date);

        $nbA=0; $nbR=0;
        foreach($page as $d)
        {
            if($d->getType()== "1")
            {
                $nbA = $nbA +1 ;
            }
            else if ($d->getType()== "2")
            {
                $nbR = $nbR +1 ;
            }
        }

        return $this->render('Front/Teacher/Discipline/show.html.twig', [
            'page' => $page,
            'nbA' => $nbA ,
            'nbR' => $nbR ,
            'teacher' => $teacher,
            'section' => $section,
            'parameters' => $param ,
        ]);
    }

    /**
     * @Route("{teacher}/call/{section}", name="teacher_discipline_call", methods={"GET" ,"POST"})
     */
    public function call(Teacher $teacher, Section $section, StudentRepository $repoSt ,SeanceRepository $seanceRepository,ParameterRepository $repoP,Request $request ): Response
    {   
        $param=$repoP->find(1); 

        $disciplines=[];

        $students=$section->getStudents();
        $tab=[];
        foreach($students as $st)
        {
            $tab[$st->getId()]=$st->getLastName();
        }
        asort($tab);
        $sorted=[];
        foreach($tab as $k => $v )
        {
            $sorted[]=$repoSt->findById($k)[0];
        }

        foreach($sorted as $s)
            {
                $Discipline = new Discipline($s);
                $Discipline->setStudent($s); 
                $disciplines[]=$Discipline;
            }

        $data["disciplines"]=$disciplines ;
        
        $form = $this->createForm(RegisterType::class, $data);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            foreach($disciplines as $d)
            {

                $date=new \DateTime('now');
                $d->setDate($date);

                $seances=$seanceRepository->findByTeaching($teacher,$section);
                $seance=$seanceRepository->findByDate($date , $seances);
                $d->setSeance($seance);

                $entityManager->persist($d);
                $entityManager->flush();
            }
            $id=$teacher->getId();
            $this->addFlash('success' , 'Fait  avec succés!');
            return $this->redirectToRoute('teacher_discipline_index', [
                'teacher' => $id,
                'section' => $section->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }
        
       
        return $this->render('Front/Teacher/Discipline/call.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $repoP->find(1) 
        ]);
    }

      /**
     * @Route("{teacher}/edit/{section}/{date}", name="teacher_discipline_edit", methods={"GET" ,"POST"})
     */
    public function edit(Teacher $teacher ,Section $section, $date ,SeanceRepository $seanceRepository ,DisciplineRepository $disciplineRepository,ParameterRepository $repoP,Request $request ): Response
    {  
        $param=$repoP->find(1);

        $seances=$seanceRepository->findByTeaching($teacher,$section);
        
        $register=$disciplineRepository->findbySeances($seances );

        $page=$disciplineRepository->findByDate($register,$date);

        $data["disciplines"]=$page ;
        
        $form = $this->createForm(RegisterType::class, $data);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $this->getDoctrine()->getManager()->flush();

            $id=$teacher->getId();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('teacher_discipline_index', [
                'teacher' => $id,
                'section' => $section->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }
        
       
        return $this->render('Front/Teacher/Discipline/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
            'parameters' => $repoP->find(1) 
        ]);
    }

    
   

    
}