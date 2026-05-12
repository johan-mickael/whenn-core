<?php

declare(strict_types=1);

namespace App\Application\Auth\CommandHandler;

use App\Application\Auth\Command\RegisterUserCommand;
use App\Domain\Common\Security\PasswordHasherInterface;
use App\Domain\Common\Transaction\TransactionManagerInterface;
use App\Domain\User\Rule\UserEmailMustBeUnique;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\UserId;
use Psr\Clock\ClockInterface;

final readonly class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $users,
        private UserEmailMustBeUnique $userEmailMustBeUnique,
        private PasswordHasherInterface $passwordHasher,
        private TransactionManagerInterface $transaction,
        private ClockInterface $clock,
    ) {}

    public function __invoke(RegisterUserCommand $command): User
    {
        $passwordHash = $this->passwordHasher->hash($command->password->toString());

        $user = User::register(
            id: UserId::generate(),
            email: $command->email,
            passwordHash: $passwordHash,
            registeredAt: $this->clock->now(),
            firstName: $command->firstName,
            lastName: $command->lastName,
        );

        $this->userEmailMustBeUnique->assert($user);

        $this->users->save($user);
        $this->transaction->flush();

        return $user;
    }
}
