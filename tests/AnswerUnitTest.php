<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Answer;
use App\Entity\Comment;
use \DateTime;

class AnswerUnitTest extends TestCase
{
    public function testEntityEmpty(): void
    {
        $answer = new Answer();

        $this->assertEmpty($answer->getId());
        $this->assertEmpty($answer->getContent());
        $this->assertEmpty($answer->getUser());
        $this->assertEmpty($answer->getComment());
        $this->assertNotEmpty($answer->getCreatedAt());
        $this->assertNotEmpty($answer->geUpdatedAt());
    }

    public function testEntityFieldValue(): void
    {
        $answer = new Answer();
        $user = new User;
        $comment = new Comment;

        $answer->setContent("Contenu de test");
        $answer->setComment($comment);
        $answer->setUser($user);

        $this->assertFalse($answer->getId() === 1);
        $this->assertFalse($answer->getContent() === "Lorem ipsum");
        $this->assertFalse($answer->getUser() === new User);
        $this->assertFalse($answer->getComment() === new Comment);
        $this->assertFalse($answer->getCreatedAt() === new DateTime());
        $this->assertFalse($answer->geUpdatedAt() === new DateTime);
    }
}
