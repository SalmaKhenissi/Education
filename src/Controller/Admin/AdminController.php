<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Form\AdminType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/student/edit")
 */
class AdminController extends AbstractController
{
   
      
    
     /**
     * @Route("/data/{id}", name="admin_data_edit", methods={"GET" ,"POST"})
     */
    public function editD(Admin $admin  , Request $request ): Response
    {   

        
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($admin->getImageFile()!=null )
            {
                if($admin->getImageName()!="inconnu")
                {
                unlink(getcwd().'\uploads\photos\\'.$admin->getImageName());
                }
                $admin->setImageName(null);
            }

            $admin->setUpdatedAt(new \DateTime('now')); 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            return $this->redirectToRoute('admin_dashbord' );
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/editAdmin.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    

   
}
