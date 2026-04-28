<?php

declare(strict_types=1);

namespace App\Application\Auth\Command;

use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\Role;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterUserHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly UserRepositoryInterface $users,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly TransactionManagerInterface $transaction,
    ) {
    }

    public function __invoke(RegisterUser $command): User
    {
        $tenant = $this->tenants->findBySlug($command->tenantSlug)
            ?? throw TenantNotFound::forSlug($command->tenantSlug);

        $existing = $this->users->findByTenantAndEmail(
            $tenant->getId(),
            (string) $command->email
        );

        if ($existing !== null) {
            throw UserAlreadyExists::forEmail((string) $command->email);
        }

        $user = new User(
            tenant: $tenant,
            email: $command->email,
            passwordHash: '',
            role: Role::BUYER,
            firstName: $command->firstName,
            lastName: $command->lastName,
        );

        $user->setPasswordHash(
            $this->hasher->hashPassword($user, $command->password->toString())
        );

        $this->users->save($user);
        $this->transaction->flush();

        return $user;
    }
}