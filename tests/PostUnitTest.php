<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Post;
use App\Entity\Comment;
use \DateTime;

class PostUnitTest extends TestCase
{
    public function testEntityEmpty(): void
    {
        $post = new Post();

        $this->assertEmpty($post->getId());
        $this->assertEmpty($post->getTitle());
        $this->assertEmpty($post->getContent());
        $this->assertNotEmpty($post->getCreatedAt());
        $this->assertNotEmpty($post->geUpdatedAt());
    }

    public function testEntityFieldValue(): void
    {
        $post = new Post();

        $post->setTitle("Titre de test");
        $post->setContent("Contenu de test");

        $this->assertFalse($post->getId() === 1);
        $this->assertFalse($post->getTitle() === "Kezako");
        $this->assertFalse($post->getContent() === "Lorem ipsum");
        $this->assertFalse($post->getCreatedAt() === new DateTime());
        $this->assertFalse($post->geUpdatedAt() === new DateTime);
    }

    public function testEntityMethods(): void
    {
        $post = new Post();
        $comment = new Comment();

        $this->assertEmpty($post->getComments());

        $post->addComment($comment);
        $this->assertContains($comment, $post->getComments());

        $post->removeComment($comment);
        $this->assertEmpty($post->getComments());
    }
}
