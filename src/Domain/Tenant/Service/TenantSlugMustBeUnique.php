<?php

namespace App\Domain\Tenant\Service;

use App\Domain\Tenant\Exception\DuplicateTenantSlug;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\Tenant\ValueObject\Slug;

class TenantSlugMustBeUnique
{
    private TenantRepositoryInterface $tenantRepository;

    public function __construct(TenantRepositoryInterface $venueRepository)
    {
        $this->tenantRepository = $venueRepository;
    }

    public function assert(Slug $slug): void
    {
        if (empty($this->tenantRepository->findBySlug($slug))) {
            return;
        }

        throw DuplicateTenantSlug::forSlug($slug);
    }
}
