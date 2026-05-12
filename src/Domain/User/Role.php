<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Security\Permission;
use App\Domain\User\Exception\InvalidRole;
use Exception;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case STAFF = 'STAFF';
    case BUYER = 'BUYER';
    case USER = 'USER';

    public static function fromString(string $value): self
    {
        return self::tryFrom($value)
            ?? throw InvalidRole::fromValue($value);
    }

    public function inheritedRoles(): array
    {
        return match ($this) {
            self::ADMIN => [
                self::ADMIN,
                self::STAFF,
                self::BUYER,
                self::USER,
            ],

            self::STAFF => [
                self::STAFF,
                self::BUYER,
                self::USER,
            ],

            self::BUYER => [
                self::BUYER,
                self::USER,
            ],

            self::USER => [
                self::USER,
            ],
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                Permission::MANAGE_USERS,
                Permission::MANAGE_EVENTS,
            ],

            self::STAFF => [
                Permission::MANAGE_EVENTS,
            ],

            self::BUYER, self::USER => [],
        };
    }

    /**
     * (Not a big fan but here we go)
     * Adapter Symfony Security.
     * Example: ROLE_ADMIN
     */
    public function securityRoles(): array
    {
        return array_map(
            static fn (Role $role) => 'ROLE_' . $role->value,
            $this->inheritedRoles(),
        );
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array(
            $permission,
            $this->permissions(),
            true,
        );
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isStaff(): bool
    {
        return $this === self::ADMIN
            || $this === self::STAFF;
    }
}
