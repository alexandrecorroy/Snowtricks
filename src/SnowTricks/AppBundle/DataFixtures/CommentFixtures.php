<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21/05/2018
 * Time: 10:24
 */

namespace SnowTricks\AppBundle\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SnowTricks\AppBundle\Entity\Comment;
use SnowTricks\AppBundle\Service\Slugger;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $objectManager)
    {

        for($i=1; $i<25; $i++)
        {
            $comment = new Comment();
            $comment->setMessage('Comment '.$i);
            $comment->setTrick($this->getReference('first-trick'));
            $comment->setUser($this->getReference('first-user'));

            $objectManager->persist($comment);
        }

        $objectManager->flush();

    }

    public function getDependencies()
    {
        return array(
            TrickFixtures::class,
        );
    }

}