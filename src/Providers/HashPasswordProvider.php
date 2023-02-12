<?php

namespace App\Providers;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordProvider
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {}

    public function hashPassword(string $plainPassword): string
    {
        $hashedPassword = $this->hasher->hashPassword(
            new User(),
            $plainPassword
        );

        return $hashedPassword;
    }
}