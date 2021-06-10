<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 14:20
 */

namespace App\Manager;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\PasswordEncoder;
use App\Service\RemoveFile;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserManager
{
    private $removeFile;
    private $em;
    private $user;
    private $flashBag;
    private $tokenGenerator;
    private $passwordEncoder;
    private $mailer;

    public function __construct(RemoveFile $removeFile, EntityManagerInterface $em, User $user, FlashBagInterface $flashBag, TokenGenerator $tokenGenerator, PasswordEncoder $passwordEncoder, Mailer $mailer)
    {
        $this->removeFile = $removeFile;
        $this->em = $em;
        $this->user = $user;
        $this->flashBag = $flashBag;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    public function initUser()
    {
        return $this->user;
    }

    public function saveUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function deleteAvatar(User $user)
    {
        $this->removeFile->remove($user->getPicture());

        $user->setPicture(null);

        $this->saveUser($user);
    }

    public function updateUser($oldAvatar, User $user)
    {
        $user = $this->compareAvatars($oldAvatar, $user);

        $this->saveUser($user);
    }

    public function compareAvatars($oldAvatar, User $user)
    {
        $newAvatar = $user->getPicture();

        if ($oldAvatar !== null && $newAvatar !== null) {
            $this->removeFile->remove($oldAvatar);
        } elseif ($oldAvatar !== null) {
            $user->setPicture($oldAvatar);
        }

        return $user;
    }

    public function activeUser(User $user)
    {
        if (!$user->isEnabled()) {
            $user->setIsActive(true);
            $this->saveUser($user);

            $this->flashBag->add(
                'notice',
                'Your account is active !'
            );
        } else {
            $this->flashBag->add(
                'notice',
                'Account already active !'
            );
        }
    }

    public function createUser(User $user)
    {

        $user->setToken($this->tokenGenerator->generateToken($user));

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->saveUser($user);

        $this->mailer->sendMail($user, 'Confirm account', 'registration');

    }

    public function resetPassword(User $user)
    {
        if ($user) {

            $user->setToken($this->tokenGenerator->generateToken($user));

            $this->saveUser($user);

            $this->mailer->sendMail($user, 'Reset your password', 'forgot_password');
        }
    }

    public function updatePassword(User $user, User $userToken, $password)
    {
        // compare si userToken et user === username
        if ($user->getUsername() === $userToken->getUsername()) {

            // on modifie le mot de passe de user
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

            $this->saveUser($user);
        }
    }
}
