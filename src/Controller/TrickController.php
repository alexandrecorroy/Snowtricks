<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/04/2018
 * Time: 14:24
 */

namespace App\Controller;

use App\Entity\Trick;
use App\Form\Type\CommentType;
use App\Form\Type\TrickType;
use App\Manager\CommentManager;
use App\Manager\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/add", name="snow_tricks_trick_add", methods={"GET","POST"})
     */
    public function addAction(Request $request, TrickManager $trickManager)
    {
        $trick = $trickManager->initTrick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->createTrick($trick);

            $this->addFlash(
                'notice',
                'Trick Added !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('Trick/form.html.twig', array(
            'form' => $form->createView(),
            'errors' => $form->getErrors()
        ));
    }

    /**
     * @Route("/trick/edit/{id}", requirements={"id" = "\d+"}, name="snow_tricks_trick_edit", methods={"GET","POST"})
     * @ParamConverter("trick", class="App:Trick")
     */
    public function editAction(Trick $trick, Request $request, TrickManager $trickManager)
    {
        $oldTrick = $trickManager->saveOldTrick($trick);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trickManager->editTrick($oldTrick, $trick);

            $this->addFlash(
                'notice',
                'Trick Edited !'
            );

            return $this->redirectToRoute('snow_tricks_trick_view', array(
                'slug' => $trick->getSlug(),
                'id' => $trick->getId()
            ));
        }

        return $this->render('Trick/form.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Route("/trick/delete/{id}/{csrf}", requirements={"id" = "\d+"}, name="snow_tricks_trick_delete", methods={"GET"})
     * @ParamConverter("trick", class="App:Trick")
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
        } else {
            throw new InvalidCsrfTokenException('Bad Csrf Token');
        }
    }

    /**
     * @Route("/trick/{id}/{slug}", name="snow_tricks_trick_view", methods={"GET","POST"})
     * @ParamConverter("trick", class="App:Trick")
     */
    public function viewAction(Trick $trick, CommentManager $commentManager, Request $request)
    {
        $comment = $commentManager->initComment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentManager->saveComment($comment, $trick);

            $this->addFlash(
                'notice',
                'Comment Added !'
            );

            return $this->redirectToRoute('snow_tricks_trick_view', array(
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ));
        }

        return $this->render('Trick/view_trick.twig', array(
            'trick' => $trick,
            'comments' => $commentManager->getFirstComments($trick),
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/", name="snow_tricks_homepage", methods={"GET"})
     */
    public function listTricksAction(TrickManager $trickManager)
    {
        return $this->render('App/index.html.twig', array(
            'tricks' => $trickManager->listFirstTricks()
        ));
    }

    /**
     * @Route("/trick/ajax/{last_trick_id}", requirements={"last_trick_id" = "\d+|null"}, name="snow_tricks_list_tricks_ajax", methods={"GET"})
     */
    public function listTricksAjaxAction($last_trick_id, TrickManager $trickManager)
    {
        $response = new JsonResponse();

        $view = $this->renderView('Trick/list_tricks_template.twig', array(
            'tricks' => $trickManager->jsonResponseOnTricks($last_trick_id)
        ));

        return $response->setData($view);
    }
}
