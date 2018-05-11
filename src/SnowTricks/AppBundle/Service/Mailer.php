<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/05/2018
 * Time: 17:08
 */

namespace SnowTricks\AppBundle\Service;


use SnowTricks\AppBundle\Entity\User;

class Mailer
{

    private $twig;
    private $mailer;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendMail(User $user, $subject, $template)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('contact@snowtricks.com')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render(
                '@SnowTricksApp/User/Emails/'.$template.'.html.twig', array(
                    'username' => $user->getUsername(),
                    'token' => $user->getToken())),
                'text/html'
            );
        $this->mailer->send($message);
    }

}