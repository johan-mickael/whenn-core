<?php

declare(strict_types=1);

namespace App\Domain\Venue;

use App\Domain\Venue\ValueObject\Address;
use App\Domain\Venue\ValueObject\VenueId;

interface VenueRepositoryInterface
{
    /**
     * @return Venue[]
     */
    public function listVenues(): array;
    public function getById(VenueId $id): Venue;
    public function save(Venue $venue): void;
    public function remove(Venue $venue): void;
    public function findByName(string $name): ?Venue;
    public function findByAddress(Address $address): ?Venue;
}
