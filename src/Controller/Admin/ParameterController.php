<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Entity\Parameter;
use App\Entity\Description;
use App\Form\ParameterType;
use App\Form\DescriptionType;
use App\Repository\ImageRepository;
use App\Repository\ParameterRepository;
use App\Repository\DescriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/parameters")
 */
class ParameterController extends AbstractController
{
    /**
     * @Route("/index", name="admin_parameters", methods={"GET"})
     */
    public function index(ParameterRepository $repoP ,DescriptionRepository $repoD ,ImageRepository $repoI , Request $request ,PaginatorInterface $paginator): Response
    {
        $images=$paginator->paginate( $repoI->findAll(), 
                                        $request->query->getInt('page', 1),
                                        4
        );
        return $this->render('Admin/Parameter/index.html.twig', [
            'parameter' => $repoP->find(1) ,
            'descriptions' => $repoD->findAll(),
            'images' => $images
        ]);
    }


    /**
     * @Route("/edit/general", name="admin_parameter_edit_general", methods={"GET","POST"})
     */
    public function editGeneral(Request $request ,ParameterRepository $repo ): Response
    {
        $parameter=$repo->find(1);
       
        $form = $this->createForm(ParameterType::class, $parameter);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parameter->setUpdatedAt(new \DateTime('now'));
            
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_parameters' );
       }

    
       return $this->render('Admin/Parameter/edit_general.html.twig', [
        'parameter' => $parameter,
        'form' => $form->createView(),
       ]);
    }
    /**
     * @Route("/index/desc", name="admin_parameters_desc", methods={"GET"})
     */
    public function indexDesc(DescriptionRepository $repoD ): Response
    {
        return $this->render('Admin/Parameter/index_desc.html.twig', [
            'descriptions' => $repoD->findAll()
        ]);
    }
    /**
     * @Route("/show/desc/{id}", name="admin_parameter_show_desc", methods={"GET"})
     */
    public function show(Description $desc): Response
    {
        return $this->render('Admin/Parameter/show.html.twig', [
            'description' => $desc ,
        ]);
    }

    /**
     * @Route("/edit/desc/{id}", name="admin_parameter_edit_desc", methods={"GET","POST"})
     */
    public function editDesc(Request $request ,Description $desc ): Response
    {
       
        $form = $this->createForm(DescriptionType::class, $desc);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $desc->setUpdatedAt(new \DateTime('now'));
            
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_parameters' );
       }

    
       return $this->render('Admin/Parameter/edit_desc.html.twig', [
        'desc' => $desc,
        'form' => $form->createView(),
       ]);
    }

    /**
     * @Route("/index/images", name="admin_parameters_image", methods={"GET"})
     */
    public function indexImage(ImageRepository $repoI , Request $request ,PaginatorInterface $paginator): Response
    {
        $images=$paginator->paginate( $repoI->findAll(), 
                                        $request->query->getInt('page', 1),
                                        4
        );
        return $this->render('Admin/Parameter/index_image.html.twig', [
            'images' => $images
        ]);
    }

    /**
     * @Route("/edit/image/{id}", name="admin_parameter_edit_image", methods={"GET","POST"})
     */
    public function editImage(Request $request ,Image $image ): Response
    {
       
        $form = $this->createForm(ImageType::class, $image);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image->setUpdatedAt(new \DateTime('now'));
            
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_parameters' );
       }

    
       return $this->render('Admin/Parameter/edit_image.html.twig', [
        'image' => $image,
        'form' => $form->createView(),
       ]);
    }

    


   

}
