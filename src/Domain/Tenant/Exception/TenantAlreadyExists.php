<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Exception;

final class TenantAlreadyExists extends \DomainException
{
    public static function forSlug(string $slug): self
    {
        return new self("Tenant with slug '{$slug}' already exists.");
    }
}
