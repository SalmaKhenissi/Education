<?php

namespace App\Controller\Admin;

use App\Entity\Parameter;
use App\Form\ParameterType;
use App\Repository\ParameterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/parameter")
 */
class ParameterController extends AbstractController
{
    /**
     * @Route("/parameters", name="admin_parameter_show", methods={"GET"})
     */
    public function show(ParameterRepository $repo): Response
    {
        return $this->render('Admin/Parameter/show.html.twig', [
            'parameter' => $repo->find(1)
        ]);
    }

  


    /**
     * @Route("/edit", name="admin_parameter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request ,ParameterRepository $repo ): Response
    {
        $parameter=$repo->find(1);
       
        $form = $this->createForm(ParameterType::class, $parameter);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_parameter_show' );
       }

    
       return $this->render('Admin/Parameter/edit.html.twig', [
        'parameter' => $parameter,
        'form' => $form->createView(),
       ]);
    }

   

}
