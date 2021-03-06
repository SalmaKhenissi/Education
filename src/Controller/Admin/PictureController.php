<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN" , statusCode=404)
 * @Route("/admin/picture")
 */
class PictureController extends AbstractController
{
    /**
     * @Route("/index", name="admin_picture_index", methods={"GET"})
     */
    public function index(PictureRepository $pictureRepository , Request $request ,PaginatorInterface $paginator): Response
    {
        $pictures=$paginator->paginate($pictureRepository->findAll(), 
                                        $request->query->getInt('page', 1),
                                        5
        );
        return $this->render('Admin/Picture/index.html.twig', [
            'pictures' => $pictures,
        ]);
    }

    /**
     * @Route("/new", name="admin_picture_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture->setUpdatedAt(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->flush();
            $this->addFlash('success' , 'Ajouté  avec succés!');

            return $this->redirectToRoute('admin_picture_index');
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Picture/new.html.twig', [
            'picture' => $picture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_picture_show", methods={"GET"})
     */
    public function show(Picture $picture): Response
    {
        return $this->render('Admin/Picture/show.html.twig', [
            'picture' => $picture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_picture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Picture $picture): Response
    {
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);
        $imageName=$picture->getImageName();

        if ($form->isSubmitted() && $form->isValid()) {
            if($picture->getImageFile()!=null)
            {
                unlink(getcwd().'\uploads\pictures\\'.$picture->getImageName());
                $picture->setImageName(null);
            }
            $picture->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success' , 'Modifié  avec succés!');

            return $this->redirectToRoute('admin_picture_index', [
                'id' => $picture->getId(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('fail' , 'Essayer de remplir votre formulaire correctement!');
        }

        return $this->render('Admin/Picture/edit.html.twig', [
            'picture' => $picture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_picture_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Picture $picture): Response
    {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();
            $this->addFlash('success' , 'Supprimé  avec succés!');

        return $this->redirectToRoute('admin_picture_index');
    }
}
