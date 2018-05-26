<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/05/2018
 * Time: 11:06
 */

namespace Tests\AppBundle\Form\Type;


use SnowTricks\AppBundle\Entity\Comment;
use SnowTricks\AppBundle\Form\Type\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            'message' => 'hello world !'
        );

        $objectToCompare = new Comment();

        $form = $this->factory->create(CommentType::class, $objectToCompare);

        $object = new Comment();
        $object->setMessage('hello world !');
        $object->setCreateDate($objectToCompare->getCreateDate());

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
