<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 10:48
 */

namespace SnowTricks\AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SnowTricks\AppBundle\Entity\Comment;
use SnowTricks\AppBundle\Entity\Trick;

class CommentManager
{
    private $comment;
    private $em;

    public function __construct(Comment $comment, EntityManagerInterface $em)
    {
        $this->comment = $comment;
        $this->em = $em;
    }

    public function initComment()
    {
        return $this->comment;
    }

    public function saveComment(Comment $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function getFirstComments(Trick $trick)
    {
        return $this->em->getRepository('SnowTricksAppBundle:Comment')->findCommentsByTrick($trick->getId());
    }
}
