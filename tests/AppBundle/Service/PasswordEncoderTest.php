<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24/05/2018
 * Time: 23:11
 */

namespace Tests\AppBundle\Service;


use PHPUnit\Framework\TestCase;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Service\PasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderTest extends TestCase
{
    protected $encoder;

    public function setUp()
    {
        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->getMock();

    }


    public function testEncodePassword()
    {

        $user = new User();
        $user->setUsername('test');
        $user->setPassword('test');

        $passwordEncoder = new PasswordEncoder($this->encoder);
        $result = $passwordEncoder->encodePassword($user, $user->getPassword());

        $this->assertEquals(60, strlen($result));
    }

}
