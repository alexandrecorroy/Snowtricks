<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24/05/2018
 * Time: 23:02
 */

namespace Tests\AppBundle\Service;


use PHPUnit\Framework\TestCase;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Service\TokenGenerator;

class TokenGeneratorTest extends TestCase
{

    public function testGenerateToken()
    {
        $user = new User();
        $user->setEmail('test@gmail.com');
        $tokenGenerator = new TokenGenerator();
        $result = $tokenGenerator->generateToken($user);

        $this->assertEquals(128, strlen($result));
    }

}
