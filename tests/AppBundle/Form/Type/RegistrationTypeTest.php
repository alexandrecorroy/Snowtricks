<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/05/2018
 * Time: 09:43
 */

namespace Tests\AppBundle\Form\Type;


use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Form\Type\RegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            'username' => 'usertest',
            'password' => 'usertest',
            'email' => 'teste@gmail.com',
        );

        $objectToCompare = new User();

        $form = $this->factory->create(RegistrationType::class, $objectToCompare);

        $object = new User();
        $object->setUsername('usertest');
        $object->setPassword('usertest');
        $object->setEmail('teste@gmail.com');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}
