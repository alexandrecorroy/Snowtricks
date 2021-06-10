<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14/05/2018
 * Time: 10:48
 */

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentManager
{
    private $comment;
    private $em;
    private $tokenStorage;

    public function __construct(Comment $comment, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->comment = $comment;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function initComment()
    {
        return $this->comment;
    }

    public function saveComment(Comment $comment, Trick $trick)
    {
        $comment->setUser($this->tokenStorage->getToken()->getUser());
        $comment->setTrick($trick);

        $this->em->persist($comment);
        $this->em->flush();
    }

    public function getFirstComments(Trick $trick)
    {
        return $this->em->getRepository(Comment::class)->findCommentsByTrick($trick->getId());
    }
}
