<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Exception;

final class TenantNotFound extends \DomainException
{
    public static function forSlug(string $slug): self
    {
        return new self("Tenant '{$slug}' not found.");
    }

    public static function forId(string $id): self
    {
        return new self("Tenant '{$id}' not found.");
    }
}
