<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/04/2018
 * Time: 14:24
 */

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\Comment;
use SnowTricks\AppBundle\Entity\Trick;
use SnowTricks\AppBundle\FormType\CommentType;
use SnowTricks\AppBundle\FormType\TrickType;
use SnowTricks\AppBundle\Manager\CommentManager;
use SnowTricks\AppBundle\Manager\TrickManager;
use SnowTricks\AppBundle\Service\Slugger;
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
    public function addAction(Request $request, TrickManager $trickManager, Slugger $slugger)
    {
        $trick = $trickManager->initTrick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->addPictures($trick->getPictures());
            $trickManager->addVideos($trick->getVideos());

            // add slug
            $trick->setSlug($slugger->slugify($trick->getName()));

            $trickManager->saveTrick($trick);

            $this->addFlash(
                'notice',
                'Trick Added !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
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
    public function editAction(Trick $trick, Request $request, TrickManager $trickManager, Slugger $slugger)
    {

        $oldTrick = $trickManager->saveOldTrick($trick);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $trickManager->comparePicturesTrick($oldTrick, $trick);

            $trickManager->addVideos($trick->getVideos());

            // update edit date
            $trick->setEditDate(new \DateTime());

            // update slug
            $trick->setSlug($slugger->slugify($trick->getName()));

            $trickManager->saveTrick($trick);

            $this->addFlash(
                'notice',
                'Trick Edited !'
            );

            return $this->redirectToRoute('snow_tricks_trick_view', array(
                'slug' => $trick->getSlug()
            ));
        }

        return $this->render('@SnowTricksApp/Trick/form.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/trick/delete/{id}/{csrf}", requirements={"id" = "\d+"}, name="snow_tricks_trick_delete")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function deleteAction(Trick $trick, $csrf, TrickManager $trickManager)
    {

        if ($this->isCsrfTokenValid('delete-item', $csrf)) {

            $trickManager->deleteTrick($trick);

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
     * @Route("/trick/{id}/{slug}", name="snow_tricks_trick_view")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function viewAction(Trick $trick, CommentManager $commentManager, Request $request)
    {

        $comment = $commentManager->initComment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($this->getUser());
            $comment->setTrick($trick);
            $commentManager->saveComment($comment);

            $this->addFlash(
                'notice',
                'Trick Added !'
            );

            return $this->redirectToRoute('snow_tricks_trick_view', array(
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ));

        }

        return $this->render('@SnowTricksApp/Trick/view_trick.twig', array(
            'trick' => $trick,
            'comments' => $commentManager->getFirstComments($trick),
            'form' => $form->createView()
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
     * @Route("/trick/ajax/{last_trick_id}", requirements={"last_trick_id" = "\d+|null"}, name="snow_tricks_list_tricks_ajax")
     */
    public function listTricksAjaxAction($last_trick_id)
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository('SnowTricksAppBundle:Trick')->findOtherTricks($last_trick_id);

        $view = $this->renderView('@SnowTricksApp/Trick/list_tricks_template.twig', array(
            'tricks' => $tricks,
        ));

        $response = new JsonResponse();
        return $response->setData($view);
    }

}
