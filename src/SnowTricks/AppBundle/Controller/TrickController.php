<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/04/2018
 * Time: 14:24
 */

namespace SnowTricks\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\Trick;
use SnowTricks\AppBundle\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class TrickController extends Controller
{
    /**
     * @Route("/trick/add", name="snow_tricks_trick_add")
     */
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

                // add slug
                $trick->setSlug($trick->getName());


                $em->persist($trick);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Trick Added !'
                );

                return $this->redirectToRoute('snow_tricks_homepage');
            }
        }

        return $this->render('@SnowTricksApp/Trick/form.html.twig', array(
            'form' => $form->createView(),
            'errors' => $form->getErrors()
        ));
    }

    /**
     * @Route("/trick/edit/{id}", requirements={"id" = "\d+"}, name="snow_tricks_trick_edit")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function editAction(Trick $trick, Request $request)
    {

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

                // update slug
                $trick->setSlug($trick->getName());


                $em->persist($trick);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Trick Edited !'
                );

                return $this->redirectToRoute('snow_tricks_trick_view', array(
                    'slug' => $trick->getSlug()
                ));
            }
        }

        return $this->render('@SnowTricksApp/Trick/form.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    public function removeFile($fileName)
    {
        unlink($this->container->getParameter('pictures_directory').'/'.$fileName);
    }

    /**
     * @Route("/trick/delete/{id}/{csrf}", requirements={"id" = "\d+"}, name="snow_tricks_trick_delete")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function deleteAction(Trick $trick, $csrf)
    {

        if ($this->isCsrfTokenValid('delete-item', $csrf)) {

            // delete all picture from server
            foreach ($trick->getPictures() as $picture) {
                $this->removeFile($picture->getFile());
            }

            // delete frontPicture from server
            if($trick->getFrontPicture() !== null)
            {
                $this->removeFile($trick->getFrontPicture());
            }

            $em = $this->getDoctrine()->getManager();
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

    /**
     * @Route("/trick/{slug}", name="snow_tricks_trick_view")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function viewAction(Trick $trick)
    {
        return $this->render('@SnowTricksApp/Trick/view_trick.twig', array(
            'trick' => $trick
        ));
    }

    public function listTricksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository('SnowTricksAppBundle:Trick')->listTricks();

        if (null === $tricks) {
            throw new NotFoundHttpException("No tricks found.");
        }

        return $this->render('@SnowTricksApp/Trick/list_tricks_template.twig', array(
            'tricks' => $tricks
        ));
    }

    /**
     * @Route("/trick/ajax/{id}", name="snow_tricks_list_tricks_ajax")
     */
    public function listTricksAjaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository('SnowTricksAppBundle:Trick')->findOtherTricks($id);

        $view = $this->renderView('@SnowTricksApp/Trick/list_tricks_template.twig', array(
            'tricks' => $tricks,
        ));

        $response = new JsonResponse();
        return $response->setData($view);
    }
}
