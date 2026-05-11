<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\ValueObject\Slug;
use App\Infrastructure\Persistence\Doctrine\Entity\TenantEntity;

final class TenantMapper
{
    public static function toDomain(TenantEntity $tenantEntity): Tenant
    {
        return Tenant::create(
            $tenantEntity->id,
            $tenantEntity->name,
            new Slug($tenantEntity->slug),
            $tenantEntity->logoUrl
        );
    }

    public static function toEntity(Tenant $tenant): TenantEntity
    {
        $e = new TenantEntity();

        $e->id = $tenant->id();
        $e->name = $tenant->name();
        $e->slug = (string) $tenant->slug();
        $e->logoUrl = $tenant->logoUrl();

        $e->createdAt = new \DateTimeImmutable();
        $e->updatedAt = new \DateTimeImmutable();

        return $e;
    }
}
