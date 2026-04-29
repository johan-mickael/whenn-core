<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Tenant\CommandHandler;

use App\Application\Tenant\Command\CreateTenant;
use App\Application\Tenant\CommandHandler\CreateTenantHandler;
use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantAlreadyExists;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\Tenant\ValueObject\Slug;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateTenantHandlerTest extends TestCase
{
    private TenantRepositoryInterface&MockObject $tenants;
    private TransactionManagerInterface&MockObject $transaction;
    private CreateTenantHandler $handler;

    protected function setUp(): void
    {
        $this->tenants     = $this->createMock(TenantRepositoryInterface::class);
        $this->transaction = $this->createMock(TransactionManagerInterface::class);

        $this->handler = new CreateTenantHandler(
            tenants: $this->tenants,
            transaction: $this->transaction,
        );
    }

    public function test_creates_tenant_successfully(): void
    {
        $this->tenants->method('findBySlug')->willReturn(null);
        $this->tenants->expects($this->once())->method('save');
        $this->transaction->expects($this->once())->method('flush');

        $tenant = ($this->handler)(new CreateTenant(
            name: 'Acme Corp',
            slug: new Slug('acme'),
        ));

        $this->assertSame('Acme Corp', $tenant->getName());
        $this->assertSame('acme', $tenant->getSlug());
    }

    public function test_throws_when_slug_already_exists(): void
    {
        $existing = $this->createMock(Tenant::class);
        $this->tenants->method('findBySlug')->willReturn($existing);

        $this->expectException(TenantAlreadyExists::class);

        ($this->handler)(new CreateTenant(
            name: 'Acme Corp',
            slug: new Slug('acme'),
        ));
    }
}
