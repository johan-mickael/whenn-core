<?php

declare(strict_types=1);

namespace App\Application\Tenant\CommandHandler;

use App\Application\Tenant\Command\CreateTenant;
use App\Domain\Common\Id\IdGeneratorInterface;
use App\Domain\Common\Transaction\TransactionManagerInterface;
use App\Domain\Tenant\Service\TenantSlugMustBeUnique;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;

final class CreateTenantHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly TenantSlugMustBeUnique $tenantSlugMustBeUnique,
        private readonly TransactionManagerInterface $transaction,
        private readonly IdGeneratorInterface $idGenerator,
    ) {}

    public function __invoke(CreateTenant $command): Tenant
    {
        $tenant = Tenant::create(
            id: $this->idGenerator->generate(),
            name: $command->name,
            slug: $command->slug,
            logoUrl: $command->logoUrl,
        );

        $this->tenantSlugMustBeUnique->assert($tenant->slug());

        $this->tenants->save($tenant);
        $this->transaction->flush();

        return $tenant;
    }
}
