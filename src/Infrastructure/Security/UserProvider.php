<?php

namespace App\Infrastructure\Security;

use App\Domain\User\UserRepositoryInterface;
use LogicException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new SecurityUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new LogicException('Stateless JWT only');
    }

    public function supportsClass(string $class): bool
    {
        return true;
    }
}
