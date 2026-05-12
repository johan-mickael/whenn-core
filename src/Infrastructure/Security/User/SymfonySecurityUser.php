<?php

namespace App\Infrastructure\Security\User;

use App\Domain\User\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class SymfonySecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private DomainUser $user
    )
    {
    }

    public function getDomainUser(): DomainUser
    {
        return $this->user;
    }

    public function getUserIdentifier(): string
    {
        return $this->user->email();
    }

    public function getRoles(): array
    {
        return $this->user->role()->securityRoles();
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->user->passwordHash();
    }

    public function id(): string
    {
        return $this->user->id();
    }
}
