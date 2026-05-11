<?php

namespace App\Domain\User\Service;

use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;

class UserEmailForTenantMustBeUnique
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function assert(string $tenant, Email $email): void
    {
        if (empty($this->userRepository->findByTenantAndEmail($tenant, (string) $email))) {
            return;
        }

        throw UserAlreadyExists::create($tenant, $email);
    }
}
