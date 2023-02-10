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

        $this->em->getManager()->persist($comment);
        $this->em->getManager()->flush(); 
            }

    public function addAnswer(Answer $answer, Comment $comment)
    {
        $answer->setComment($comment);

        $this->em->getManager()->persist($answer);
        $this->em->getManager()->flush();

    }
}