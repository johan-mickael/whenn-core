<?php

declare(strict_types=1);

namespace App\Domain\Common\Security\Authorization;

interface PolicyInterface
{
    public function supports(
        object $subject,
    ): bool;

    public function authorize(
        UserContext $actor,
        Action $action,
        object $subject,
    ): bool;
}
