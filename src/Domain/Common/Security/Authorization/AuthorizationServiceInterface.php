<?php

namespace App\Domain\Common\Security\Authorization;

interface AuthorizationServiceInterface
{
    public function authorize(
        UserContext $actor,
        Action $action,
        object $resource,
    ): bool;
}
