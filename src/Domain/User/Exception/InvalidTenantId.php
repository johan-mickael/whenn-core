<?php

namespace App\Domain\User\Exception;

final class InvalidTenantId extends UserException
{
    public static function create(string $tenantId): self
    {
        return new self("Invalid Tenant ID '$tenantId'.");
    }
}
