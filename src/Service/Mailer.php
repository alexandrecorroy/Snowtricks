<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/05/2018
 * Time: 17:08
 */

namespace App\Service;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $twig;

    private $mailerFrom;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->mailerFrom = '$mailerFrom';
    }

    public function getMailerFrom()
    {
        return $this->mailerFrom;
    }

    public function sendMail(
        User $user, $subject, $template, \Swift_Mailer $mailer
    ): void
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->getMailerFrom())
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'User/Emails/' . $template . '.html.twig',
                    array(
                        'username' => $user->getUsername(),
                        'token' => $user->getToken())
                ),
                'text/html'
            );
        $mailer->send($message);
    }
}