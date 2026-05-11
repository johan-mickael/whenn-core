<?php

namespace App\Infrastructure\Security;

use App\Domain\Security\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function hash(string $plainPassword): string
    {
        return $this->hasher->hashPassword(
            new SystemUser(),
            $plainPassword
        );
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        $user = new SystemUser($hashedPassword);

        return $this->hasher->isPasswordValid(
            $user,
            $plainPassword
        );
    }
}
