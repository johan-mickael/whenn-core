<?php

declare(strict_types=1);

namespace App\Domain\Venue;

use App\Domain\Venue\ValueObject\Capacity;
use InvalidArgumentException;

final class Venue
{
    private function __construct(
        private string   $id,
        private string   $name,
        private string   $address,
        private string   $city,
        private string   $country,
        private Capacity $capacity,
        private ?string  $zipCode = null,
        private ?float   $latitude = null,
        private ?float   $longitude = null,
    )
    {
    }

    public function id(): string { return $this->id; }
    public function name(): string { return $this->name; }
    public function address(): string { return $this->address; }
    public function city(): string { return $this->city; }
    public function country(): string { return $this->country; }
    public function capacity(): Capacity { return $this->capacity; }
    public function zipCode(): ?string { return $this->zipCode; }
    public function latitude(): ?float { return $this->latitude; }
    public function longitude(): ?float { return $this->longitude; }

    public static function create(
        string   $id,
        string   $name,
        string   $address,
        string   $city,
        string   $country,
        Capacity $capacity,
        ?string  $zipCode = null,
        ?float   $latitude = null,
        ?float   $longitude = null,
    ): self
    {
        self::assertValidName($name);
        self::assertValidCapacity($capacity);

        return new self(
            $id,
            $name,
            $address,
            $city,
            $country,
            $capacity,
            $zipCode,
            $latitude,
            $longitude,
        );
    }

    public function rename(string $name): void
    {
        self::assertValidName($name);
        $this->name = $name;
    }

    public function moveTo(string $address, string $city, string $country): void
    {
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
    }

    private static function assertValidName(string $name): void
    {
        if ($name === '') {
            throw new InvalidArgumentException('Name required');
        }
    }

    private static function assertValidCapacity(Capacity $capacity): void
    {
        if ($capacity->value <= 0) {
            throw new InvalidArgumentException('Capacity invalid');
        }
    }

}
