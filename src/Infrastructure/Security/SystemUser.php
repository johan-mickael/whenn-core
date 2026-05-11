<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class SystemUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private ?string $hashedPassword = null
    ) {}

    public function setPassword(string $hashed): void
    {
        $this->hashedPassword = $hashed;
    }

    public function getUserIdentifier(): string
    {
        return 'system';
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void {}

    public function getPassword(): ?string
    {
        return $this->hashedPassword;
    }
}
