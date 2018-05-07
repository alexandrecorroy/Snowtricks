<?php

namespace SnowTricks\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SnowTricksCommentBundle:Default:index.html.twig');
    }
}
