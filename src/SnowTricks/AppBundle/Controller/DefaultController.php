<?php

namespace SnowTricks\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@SnowTricksApp/Default/index.html.twig');
    }
}
