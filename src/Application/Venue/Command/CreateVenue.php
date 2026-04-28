<?php

declare(strict_types=1);

namespace App\Application\Venue\Command;

final readonly class CreateVenue
{
    public function __construct(
        public string $tenantId,
        public string $name,
        public string $address,
        public string $city,
        public string $country,
        public int $capacity,
        public ?string $zipCode = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
        if (empty($name)) {
            throw new \InvalidArgumentException('name is required.');
        }

        if ($capacity <= 0) {
            throw new \InvalidArgumentException('capacity must be greater than 0.');
        }
    }
}
