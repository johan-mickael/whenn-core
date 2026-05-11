<?php

namespace App\Domain\Tenant\Service;

use App\Domain\Tenant\Exception\DuplicateTenantSlug;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;

class TenantSlugMustBeUnique
{
    private TenantRepositoryInterface $tenantRepository;

    public function __construct(TenantRepositoryInterface $venueRepository)
    {
        $this->tenantRepository = $venueRepository;
    }

    public function check(Tenant $tenant): void
    {
        if (empty($this->tenantRepository->findBySlug($tenant->getSlug()))) {
            return;
        }

        throw DuplicateTenantSlug::forSlug($tenant->getSlug());
    }
}
