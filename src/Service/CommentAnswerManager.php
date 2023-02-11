<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Answer;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class CommentAnswerManager
{
    public function __construct(private ManagerRegistry $em)
    {}

    /**
     * @param Comment $comment
     * @param Post $post
     * @param User $user
     */
    public function addComment(Comment $comment, Post $post, User $user)
    {
        $comment->setPost($post);
        $comment->setUser($user);

        $this->persist($comment);
    }

    /**
     * @param Comment $comment
     * @param Post $post
     * @param User $user
     */
    public function addAnswer(Answer $answer, Comment $comment, User $user)
    {
        $answer->setComment($comment);
        $answer->setUser($user);

        $this->persist($answer);
    }
    
    public function persist($entity)
    {
        $this->em->getManager()->persist($entity);
        $this->em->getManager()->flush();
    }

    public function remove($entity)
    {
        $this->em->getManager()->remove($entity);
        $this->em->getManager()->flush();
    }

    public function update()
    {
        $this->em->getManager()->flush();
    }
}