<?php

namespace App\Controller\Admin;

use App\Entity\Guardian;
use App\Form\GuardianType;
use App\Repository\UserRepository;
use App\Repository\GuardianRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/guardian")
 */
class GuardianController extends AbstractController
{
    /**
     * @Route("/index", name="admin_guardian_index", methods={"GET"})
     */
    public function index(GuardianRepository $guardianRepository , Request $request ,PaginatorInterface $paginator): Response
    {
        $firstName = $request->get('first');
        $lastName = $request->get('last');
        $guardians=$paginator->paginate($guardianRepository->findByOption($firstName , $lastName), 
                                        $request->query->getInt('page', 1),
                                        6
        );
        return $this->render('Admin/Guardian/index.html.twig', [
            'guardians' => $guardians,
        ]);
    }

    /**
     * @Route("/new", name="admin_guardian_new", methods={"GET","POST"})
     */
    public function new(Request $request ,UserRepository $userRepository): Response
    {
        $guardian = new Guardian();
        $form = $this->createForm(GuardianType::class, $guardian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guardian->setRoles('ROLE_GUARDIAN');


            $guardian->setUsername($userRepository->generateUsername($guardian) );
            $guardian->setPassword($userRepository->generatePassword($guardian) );

            $guardian->setUpdatedAt(new \DateTime('now'));

            $guardian->setImageName("inconnu");

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($guardian);
            $entityManager->flush();

            $this->addFlash('success' , 'Ajouté  avec succés!');
            return $this->redirectToRoute('admin_guardian_show' ,[
                'id' => $guardian->getId()
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Guardian/new.html.twig', [
            'guardian' => $guardian,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_guardian_show", methods={"GET"})
     */
    public function show(Guardian $guardian): Response
    {    
        return $this->render('Admin/Guardian/show.html.twig', [
            'guardian' => $guardian,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_guardian_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Guardian $guardian): Response
    { 
        $form = $this->createForm(GuardianType::class, $guardian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $guardian->setUpdatedAt(new \DateTime('now'));

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');
            
            return $this->redirectToRoute('admin_guardian_index');
        }

        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }
        
        return $this->render('Admin/Guardian/edit.html.twig', [
            'guardian' => $guardian,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_guardian_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Guardian $guardian ,GuardianRepository $guardianRepository ): Response
    {
            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($guardian);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');
        return $this->redirectToRoute('admin_guardian_index');
    }

    
}
