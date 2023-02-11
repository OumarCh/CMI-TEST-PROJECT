<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Answer;
use \DateTime;

class CommentUnitTest extends TestCase
{
    public function testEntityEmpty(): void
    {
        $comment = new Comment();

        $this->assertEmpty($comment->getId());
        $this->assertEmpty($comment->getContent());
        $this->assertEmpty($comment->getUser());
        $this->assertEmpty($comment->getAnswers());
        $this->assertNotEmpty($comment->getCreatedAt());
        $this->assertNotEmpty($comment->geUpdatedAt());
    }

    public function testEntityFieldValue(): void
    {
        $comment = new comment();
        $user = new User;

        $comment->setContent("Contenu de test");
        $comment->setUser($user);

        $this->assertFalse($comment->getId() === 1);
        $this->assertFalse($comment->getContent() === "Lorem ipsum");
        $this->assertFalse($comment->getUser() === new User);
        $this->assertFalse($comment->getCreatedAt() === new DateTime());
        $this->assertFalse($comment->geUpdatedAt() === new DateTime);
    }

    public function testEntityMethods(): void
    {
        $comment = new comment();
        $answer = new Answer();

        $this->assertEmpty($comment->getAnswers());

        $comment->addAnswer($answer);
        $this->assertContains($answer, $comment->getAnswers());

        $comment->removeAnswer($answer);
        $this->assertEmpty($comment->getAnswers());
    }
}
