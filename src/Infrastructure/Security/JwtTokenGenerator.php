<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class JwtTokenGenerator
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function generateFor(User $user): string
    {
        return $this->jwtManager->create($user);
    }
}