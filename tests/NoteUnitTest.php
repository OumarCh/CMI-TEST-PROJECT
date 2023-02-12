<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Note;
use \DateTime;

class NoteUnitTest extends TestCase
{
    public function testEntityEmpty(): void
    {
        $note = new Note();

        $this->assertEmpty($note->getId());
        $this->assertEmpty($note->getNote());
        $this->assertEmpty($note->getComment());
        $this->assertEmpty($note->getUser());
        $this->assertNotEmpty($note->getCreatedAt());
        $this->assertNotEmpty($note->geUpdatedAt());
    }

    public function testEntityFieldValue(): void
    {
        $note = new Note();
        $user = new User();
        $comment = new Comment();

        $note->setNote(3);
        $note->setComment($comment);
        $note->setUser($user);

        $this->assertFalse($note->getId() === 1);
        $this->assertFalse($note->getUser() === new User());
        $this->assertFalse($note->getComment() === new Comment());
        $this->assertFalse($note->getCreatedAt() === new DateTime());
        $this->assertFalse($note->geUpdatedAt() === new DateTime);
    }
}
