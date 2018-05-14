<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 14:36
 */

namespace SnowTricks\AppBundle\Service;


class TokenGenerator
{

    public static function generateToken(User $user)
    {
        $data = $user->getEmail().uniqid().microtime();
        return hash('sha512', $data);
    }

}