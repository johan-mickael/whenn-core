<?php

declare(strict_types=1);

namespace App\Application\Tenant\CommandHandler;

use App\Application\Tenant\Command\CreateTenant;
use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantAlreadyExists;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;

final class CreateTenantHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly TransactionManagerInterface $transaction,
    ) {}

    public function __invoke(CreateTenant $command): Tenant
    {
        $existing = $this->tenants->findBySlug((string) $command->slug);

        if ($existing !== null) {
            throw TenantAlreadyExists::forSlug((string) $command->slug);
        }

        $tenant = new Tenant(
            name: $command->name,
            slug: $command->slug,
            logoUrl: $command->logoUrl,
        );

        $this->tenants->save($tenant);
        $this->transaction->flush();

        return $tenant;
    }
}
