<?php

declare(strict_types=1);

namespace App\Application\Auth\CommandHandler;

use App\Application\Auth\Command\RegisterUser;
use App\Domain\Common\Transaction\TransactionManagerInterface;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\Role;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final class RegisterUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface     $users,
        private readonly PasswordHasherInterface     $passwordHasher,
        private readonly TransactionManagerInterface $transaction,
        private readonly ClockInterface              $clock,
    )
    {
    }

    public function __invoke(RegisterUser $command): User
    {
        $passwordHash = $this->passwordHasher->hash(
            $command->password->toString()
        );

        $user = User::register(
            id: uuid_create(UUID_TYPE_RANDOM),
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
