<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     * @Route("/comment/trick/{id}/{lastComment}", requirements={"id" = "\d+", "lastComment" = "\d+|null"}, name="snow_tricks_comment_list_comment_ajax", methods={"GET"})
     */
    public function listCommentByTrickAndAjaxAction($id, $lastComment)
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findOtherComments($id, $lastComment);

        $view = $this->renderView('Comment/infinite_scroll_comments.html.twig', array(
                'comments' => $comments,
            ));

        $response = new JsonResponse();
        return $response->setData($view);
    }
}
