<?php

namespace App\Domain\Common\Security\Authorization;

interface AuthorizationServiceInterface
{
    public function authorize(
        UserContext $actor,
        Action $action,
        Subject|string $subject,
    ): bool;
}
