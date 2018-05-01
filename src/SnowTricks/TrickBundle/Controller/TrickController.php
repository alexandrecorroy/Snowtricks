<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/04/2018
 * Time: 14:24
 */

namespace SnowTricks\TrickBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use SnowTricks\CommentBundle\Controller\CommentController;
use SnowTricks\TrickBundle\Entity\Trick;
use SnowTricks\TrickBundle\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class TrickController extends Controller
{
    public function addAction(Request $request)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $files = $trick->getPictures();
                $videos = $trick->getVideos();


                foreach($files as $file)
                {

                    $trick->addPicture($file);
                }

                foreach($videos as $video)
                {

                    $trick->addVideo($video);
                }


                $em->persist($trick);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Trick Added !'
                );

                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksTrick/trick/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $trick = $em->getRepository('SnowTricksTrickBundle:Trick')->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("No trick found.");
        }


        $originalPictures = new ArrayCollection();
        $originalVideos = new ArrayCollection();
        $originalFrontPictureName = $trick->getFrontPicture();

        // initialize setFrontPictureName
        $trick->setFrontPictureName($originalFrontPictureName);

        // save and initialization setFileName on Picture
        foreach ($trick->getPictures() as $picture) {
            $originalPictures->add($picture);
            $picture->setFileName($picture->getFile());
        }

        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        }

        $form = $this->createForm(TrickType::class, $trick);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $files = $trick->getPictures();
                $videos = $trick->getVideos();

                // remove the relationship between the videos and the Trick
                foreach ($originalVideos as $video) {
                    if (false === $videos->contains($video)) {
                        $em->remove($video);
                    }
                }

                //remove the relationship between the picture and the Trick
                foreach ($originalPictures as $picture) {
                    if (false === $files->contains($picture)) {
                        $em->remove($picture);
                        $this->removeFile($picture->getFileName());
                    }
                }

                //set filename to file on unchange files and add news pictures
                foreach ($files as $file) {
                    if ($file->getId() !== null && $file->getFile() === null) {
                        $file->setFile($file->getFileName());
                    }
                    elseif ($file->getId() === null && $file->getFile() !== null)
                    {
                        $trick->addPicture($file);
                    }
                    elseif ($file->getId() !== null && $file->getFile() !== null)
                    {
                        $trick->addPicture($file);
                        $this->removeFile($file->getFileName());

                    }
                }

                //set filename to frontPicture on unchange files and add news pictures
                if($trick->getFrontPicture() === null && $trick->getFrontPictureName() !== null)
                {
                    $trick->setFrontPicture($trick->getFrontPictureName());
                }
                else
                {
                    if($trick->getFrontPictureName() !== null)
                    {
                        $this->removeFile($trick->getFrontPictureName());
                    }

                }


                // add new videos
                foreach($videos as $video)
                {
                    $trick->addVideo($video);
                }

                // update edit date
                $trick->setEditDate(new \DateTime());


                $em->persist($trick);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Trick Edited !'
                );

                return $this->redirectToRoute('snow_tricks_trick_view', array(
                    'id' => $trick->getId()
                ));
            }
        }

        return $this->render('@SnowTricksTrick/trick/form.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    public function removeFile($fileName)
    {
        unlink($this->container->getParameter('pictures_directory').'/'.$fileName);
    }

    public function deleteAction($id, $csrf)
    {

        if ($this->isCsrfTokenValid('delete-item', $csrf)) {
            $em = $this->getDoctrine()->getManager();

            $trick = $em->getRepository('SnowTricksTrickBundle:Trick')->find($id);

            if (null === $trick) {
                throw new NotFoundHttpException("No trick found.");
            }

            // delete all picture from server
            foreach ($trick->getPictures() as $picture) {
                $this->removeFile($picture->getFile());
            }

            // delete frontPicture from server
            if($trick->getFrontPicture() !== null)
            {
                $this->removeFile($trick->getFrontPicture());
            }


            $em->remove($trick);
            $em->flush();

            $this->addFlash(
                'notice',
                'Trick Deleted !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
        }
        else
            throw new InvalidCsrfTokenException('Bad Csrf Token');


    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $trick = $em->getRepository('SnowTricksTrickBundle:Trick')->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("No trick found.");
        }

        return $this->render('@SnowTricksTrick/trick/view_trick.twig', array(
            'trick' => $trick
        ));
    }

    public function listTricksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository('SnowTricksTrickBundle:Trick')->findAll(array(), array('id' => 'DESC'), 15);

        if (null === $tricks) {
            throw new NotFoundHttpException("No tricks found.");
        }

        return $this->render('@SnowTricksTrick/trick/list_tricks_template.twig', array(
            'tricks' => $tricks
        ));
    }
}
