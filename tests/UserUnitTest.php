<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use \DateTime;

class UserUnitTest extends TestCase
{
    public function testEntityEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getFirstName());
        $this->assertEmpty($user->getLastName());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getGoogleId());
        $this->assertEmpty($user->getAvatar());
        $this->assertNotEmpty($user->getCreatedAt());
        $this->assertNotEmpty($user->geUpdatedAt());
    }

    public function testEntityFieldValue(): void
    {
        $user = new user();

        $user->setFirstName("Oumar");
        $user->setLastName("Haidara");
        $user->setEmail("ohaidara@ptc.tech");
        $user->setPassword("(abcd)\klkk");
        $user->setGoogleId("123456789");
        $user->setAvatar("https://ui-avatars.com/api/?name=O");

        $this->assertFalse($user->getId() === 1);
        $this->assertFalse($user->getEmail() === "haidara@ptc.tech");
        $this->assertFalse($user->getFirstName() === "Lorem ipsum");
        $this->assertFalse($user->getLastName() === "Lorem ipsum");
        $this->assertFalse($user->getPassword() === "Lorem ipsum");
        $this->assertFalse($user->getGoogleId() === "Lorem ipsum");
        $this->assertFalse($user->getAvatar() === "Lorem ipsum");
        $this->assertFalse($user->getCreatedAt() === new DateTime());
        $this->assertFalse($user->geUpdatedAt() === new DateTime);
    }
}
