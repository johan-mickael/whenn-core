<?php

declare(strict_types=1);

namespace App\Domain\Common\Security\Authorization;

use App\Domain\User\Role;
use App\Domain\User\ValueObject\UserId;

final readonly class UserContext
{
    public function __construct(
        public UserId $id,
        public Role $role,
    ) {
    }
}
