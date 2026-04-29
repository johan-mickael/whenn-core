<?php

declare(strict_types=1);

namespace App\Application\Venue\Command;

use App\Domain\Venue\ValueObject\Capacity;

final readonly class CreateVenue
{
    public Capacity $capacity;

    public function __construct(
        public string $tenantId,
        public string $name,
        public string $address,
        public string $city,
        public string $country,
        int $capacity,
        public ?string $zipCode = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
        $this->capacity = new Capacity($capacity);
    }
}
