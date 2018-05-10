<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\Comment;
use SnowTricks\AppBundle\Form\CommentType;
use SnowTricks\AppBundle\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CommentController extends Controller
{

    /**
     * @Route("/comment/add/{id}", requirements={"id" = "\d+"}, name="snow_tricks_comment_add")
     * @ParamConverter("trick", class="SnowTricksAppBundle:Trick")
     */
    public function addAction(Trick $trick, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, array(
            'action' => $this->generateUrl('snow_tricks_comment_add', array(
                'id' => $trick->getId()
            )),
            'method' => 'POST'

        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $comment->setTrick($trick);
                $comment->setUser($this->getUser());

                $em->persist($comment);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Message Added !'
                );

                return $this->redirectToRoute('snow_tricks_trick_view', array(
                    'slug' => $trick->getSlug()
                ));
            }
        }

        return $this->render('@SnowTricksApp/Comment/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listCommentsByTrickAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('SnowTricksAppBundle:Comment')->findCommentsByTrick($id);

        return $this->render('@SnowTricksApp/Comment/comments.html.twig', array(
            'comments' => $comments
        ));
    }

    /**
     * @Route("/comment/trick/{id}/{lastComment}", requirements={"id" = "\d+", "lastComment" = "\d+|null"}, name="snow_tricks_comment_list_comment_ajax")
     */
    public function listCommentByTrickAndAjaxAction($id, $lastComment) {

            $em = $this->getDoctrine()->getManager();
            $comments = $em->getRepository('SnowTricksAppBundle:Comment')->findOtherComments($id, $lastComment);

            $view = $this->renderView('@SnowTricksApp/Comment/infinite_scroll_comments.html.twig', array(
                'comments' => $comments,
            ));

            $response = new JsonResponse();
            return $response->setData($view);
    }

}
