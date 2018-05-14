<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 14:36
 */

namespace SnowTricks\AppBundle\Service;


use SnowTricks\AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordEncoder
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function encodePassword(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

}