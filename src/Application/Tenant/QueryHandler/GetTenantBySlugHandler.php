<?php

declare(strict_types=1);

namespace App\Application\Tenant\QueryHandler;

use App\Application\Tenant\Query\GetTenantBySlug;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;

final class GetTenantBySlugHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
    ) {}

    public function __invoke(GetTenantBySlug $query): Tenant
    {
        return $this->tenants->findBySlug((string) $query->slug)
            ?? throw TenantNotFound::forSlug((string) $query->slug);
    }
}
