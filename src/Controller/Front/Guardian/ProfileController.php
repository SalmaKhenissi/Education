<?php

namespace App\Controller\Front\Guardian;

use App\Form\User3Type;
use App\Entity\Guardian;
use App\Form\Guardian2Type;
use App\Form\PWGuardianType;
use App\Repository\ParameterRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_GUARDIAN" , statusCode=404)
 * @Route("/profile/guardian/edit")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/password/{id}", name="guardian_password_edit", methods={"GET" ,"POST"})
     */
    public function editP(Guardian $guardian  ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);

        
        $form = $this->createForm(PWGuardianType::class, $guardian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guardian->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('guardian_profile' , [
                'id' => $guardian->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Guardian/Profile/editPassword.html.twig', [
            'guardian' => $guardian,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);

      
    }
     /**
     * @Route("/data/{id}", name="guardian_data_edit", methods={"GET" ,"POST"})
     */
    public function editD(Guardian $guardian  ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);

        
        $form = $this->createForm(Guardian2Type::class, $guardian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $guardian->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('guardian_profile' , [
                'id' => $guardian->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Guardian/Profile/editData.html.twig', [
            'guardian' => $guardian,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

      /**
     * @Route("/photo/{id}", name="guardian_photo_edit", methods={"GET" ,"POST"})
     */
    public function editPh(Guardian $guardian  ,ParameterRepository $repoP , Request $request ): Response
    {   
        $param=$repoP->find(1);

        
        $form = $this->createForm(User3Type::class, $guardian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($guardian->getImageFile()!=null )
            {
                if($guardian->getImageName()!="inconnu")
                {
                unlink(getcwd().'\uploads\photos\\'.$guardian->getImageName());
                }
                $guardian->setImageName(null);
            }

            $guardian->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('guardian_profile' , [
                'id' => $guardian->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Front/Guardian/Profile/editPhoto.html.twig', [
            'guardian' => $guardian,
            'form' => $form->createView(),
            'parameters' => $param ,
        ]);
    }

    

   
}
