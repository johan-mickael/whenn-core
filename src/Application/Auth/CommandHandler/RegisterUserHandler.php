<?php

declare(strict_types=1);

namespace App\Application\Auth\CommandHandler;

use App\Application\Auth\Command\RegisterUser;
use App\Domain\Common\Transaction\TransactionManagerInterface;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\Role;
use App\Domain\User\Service\UserEmailForTenantMustBeUnique;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final class RegisterUserHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface   $tenants,
        private readonly UserRepositoryInterface     $users,
        private readonly PasswordHasherInterface     $passwordHasher,
        private readonly TransactionManagerInterface $transaction,
        private readonly ClockInterface              $clock,
        private readonly UserEmailForTenantMustBeUnique  $emailForTenantMustBeUnique
    )
    {
    }

    public function __invoke(RegisterUser $command): User
    {
        $tenant = $this->tenants->findBySlug($command->tenantSlug)
            ?? throw TenantNotFound::forSlug($command->tenantSlug);

        $this->emailForTenantMustBeUnique->assert($tenant->id(), $command->email);

        $passwordHash = $this->passwordHasher->hash(
            (string) $command->email,
            $command->password->toString()
        );

        $user = User::create(
            id: uuid_create(UUID_TYPE_RANDOM),
            tenantId: $tenant->id(),
            email: $command->email,
            passwordHash: $passwordHash,
            registeredAt: $this->clock->now(),
            firstName: $command->firstName,
            lastName: $command->lastName,
        );

        $this->users->save($user);
        $this->transaction->flush();

        return $user;
    }
}
