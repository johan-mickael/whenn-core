<?php

declare(strict_types=1);

namespace App\Domain\Venue;

interface VenueRepositoryInterface
{
    /**
     * @return Venue[]
     */
    public function listVenues(): array;
    public function findById(string $id): ?Venue;
    public function save(Venue $venue): void;
    public function remove(Venue $venue): void;
    public function findByAddress(string $address): ?Venue;
}
