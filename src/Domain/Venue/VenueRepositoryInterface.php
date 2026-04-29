<?php

declare(strict_types=1);

namespace App\Domain\Venue;

interface VenueRepositoryInterface
{
    public function findById(string $id): ?Venue;
    public function findByIdAndTenant(string $id, string $tenantId): ?Venue;
    /** @return Venue[] */
    public function findByTenant(string $tenantId): array;
    public function save(Venue $venue): void;
    public function remove(Venue $venue): void;
}
