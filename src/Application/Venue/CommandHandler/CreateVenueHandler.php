<?php

declare(strict_types=1);

namespace App\Application\Venue\CommandHandler;

use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use App\Application\Venue\Command\CreateVenue;

final class CreateVenueHandler
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly VenueRepositoryInterface $venues,
        private readonly TransactionManagerInterface $transaction,
    ) {}

    public function __invoke(CreateVenue $command): Venue
    {
        $tenant = $this->tenants->findById($command->tenantId)
            ?? throw TenantNotFound::forId($command->tenantId);

        $venue = new Venue(
            tenant: $tenant,
            name: $command->name,
            address: $command->address,
            city: $command->city,
            country: $command->country,
            capacity: $command->capacity,
            zipCode: $command->zipCode,
            latitude: $command->latitude,
            longitude: $command->longitude,
        );

        $this->venues->save($venue);
        $this->transaction->flush();

        return $venue;
    }
}
