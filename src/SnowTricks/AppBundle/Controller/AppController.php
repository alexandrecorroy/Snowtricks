<?php

namespace SnowTricks\AppBundle\Controller;

use SnowTricks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public function indexAction()
    {
        return $this->render('@SnowTricksApp/App/index.html.twig');
    }

}
