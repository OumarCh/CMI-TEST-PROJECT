<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Answer;
use Doctrine\Persistence\ManagerRegistry;

class CommentAnswerManager
{
    public function __construct(private ManagerRegistry $em)
    {}

    public function addComment(Comment $comment, Post $post)
    {
        $comment->setPost($post);

        $this->persist($comment);
    }

    public function addAnswer(Answer $answer, Comment $comment)
    {
        $answer->setComment($comment);

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