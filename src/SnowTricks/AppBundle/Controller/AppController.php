<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{

    /**
     * @Route("/", name="snow_tricks_homepage")
     */
    public function indexAction()
    {
        return $this->render('@SnowTricksApp/App/index.html.twig');
    }

}
