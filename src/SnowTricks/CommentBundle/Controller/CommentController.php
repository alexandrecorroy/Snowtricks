<?php

namespace SnowTricks\CommentBundle\Controller;

use SnowTricks\CommentBundle\Entity\Comment;
use SnowTricks\CommentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{
    public function addAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $trick = $em->getRepository('SnowTricksTrickBundle:Trick')->find($id);

        if (null === $trick) {
            throw new NotFoundHttpException("No trick found.");
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, array(
            'action' => $this->generateUrl('snow_tricks_comment_add', array(
                'id' => $id
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
                    'id' => $id
                ));
            }
        }

        return $this->render('@SnowTricksComment/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listCommentsByTrickAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('SnowTricksCommentBundle:Comment')->findAll($id);

        return $this->render('@SnowTricksComment/comments.html.twig', array(
            'comments' => $comments
        ));
    }

}
