<?php

declare(strict_types=1);

namespace App\Application\Auth\QueryHandler;

use App\Application\Auth\Query\AuthenticateUser;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\User\Exception\InvalidCredentials;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

final class AuthenticateUserHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly UserRepositoryInterface   $users,
        private readonly PasswordHasherInterface   $hasher,
    )
    {
    }

    public function __invoke(AuthenticateUser $query): User
    {
        $tenant = $this->tenants->findBySlug($query->tenantSlug)
            ?? throw TenantNotFound::forSlug($query->tenantSlug);

        $user = $this->users->findByTenantAndEmail(
            $tenant->id(),
            (string)$query->email
        );

        if ($user === null) {
            throw InvalidCredentials::create();
        }

        if ($this->hasher->verify($user->passwordHash(), $query->password->toString())) {
            return $user;
        }

        throw InvalidCredentials::create();
    }
}
