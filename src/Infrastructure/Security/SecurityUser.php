<?php

namespace App\Infrastructure\Security;

use App\Domain\User\User as DomainUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private DomainUser $user
    )
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->email();
    }

    public function getRoles(): array
    {
        return ['ROLE_' . $this->user->role()->value];
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
