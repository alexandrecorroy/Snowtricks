<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 14:20
 */

namespace SnowTricks\AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Service\RemoveFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserManager
{
    private $removeFile;
    private $em;
    private $user;
    private $flashBag;

    public function __construct(RemoveFile $removeFile, EntityManagerInterface $em, User $user, FlashBagInterface $flashBag)
    {
        $this->removeFile = $removeFile;
        $this->em = $em;
        $this->user = $user;
        $this->flashBag = $flashBag;
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
}
