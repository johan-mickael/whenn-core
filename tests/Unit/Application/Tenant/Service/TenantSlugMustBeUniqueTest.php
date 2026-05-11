<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Tenant\Service;

use App\Domain\Tenant\Exception\DuplicateTenantSlug;
use App\Domain\Tenant\Service\TenantSlugMustBeUnique;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\TenantEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TenantSlugMustBeUniqueTest extends TestCase
{
    private TenantRepositoryInterface&MockObject $tenants;
    private TenantSlugMustBeUnique $service;

    protected function setUp(): void
    {
        $this->tenants = $this->createMock(TenantRepositoryInterface::class);

        $this->service = new TenantSlugMustBeUnique(
            $this->tenants,
        );
    }

    public function test_passes_when_slug_is_unique(): void
    {
        $tenant = $this->createMock(TenantEntity::class);
        $tenant->method('getSlug')->willReturn('acme');

        $this->tenants
            ->method('findBySlug')
            ->with('acme')
            ->willReturn(null);

        $this->service->check($tenant);

        $this->assertTrue(true);
    }

    public function test_throws_when_slug_already_exists(): void
    {
        $tenant = $this->createMock(TenantEntity::class);
        $existing = $this->createMock(TenantEntity::class);

        $tenant->method('getSlug')->willReturn('acme');

        $this->tenants
            ->method('findBySlug')
            ->with('acme')
            ->willReturn($existing);

        $this->expectException(DuplicateTenantSlug::class);

        $this->service->check($tenant);
    }
}
