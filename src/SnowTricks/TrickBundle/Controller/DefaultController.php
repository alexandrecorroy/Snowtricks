<?php

namespace SnowTricks\TrickBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SnowTricksTrickBundle:Default:index.html.twig');
    }
}
