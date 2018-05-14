<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{

    /**
     * @Route("/comment/trick/{id}/{lastComment}", requirements={"id" = "\d+", "lastComment" = "\d+|null"}, name="snow_tricks_comment_list_comment_ajax")
     */
    public function listCommentByTrickAndAjaxAction($id, $lastComment)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('SnowTricksAppBundle:Comment')->findOtherComments($id, $lastComment);

        $view = $this->renderView('@SnowTricksApp/Comment/infinite_scroll_comments.html.twig', array(
                'comments' => $comments,
            ));

        $response = new JsonResponse();
        return $response->setData($view);
    }
}
