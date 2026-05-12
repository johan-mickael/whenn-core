<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\User\Jwt;

use App\Domain\User\User;
use App\Infrastructure\Security\User\SymfonySecurityUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final readonly class LexikJwtTokenGenerator
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function generateFor(User $user): string
    {
        return $this->jwtManager->create(new SymfonySecurityUser($user));
    }
}
