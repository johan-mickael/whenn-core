<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\Venue\Venue;
use App\Domain\Venue\ValueObject\Capacity;
use App\Infrastructure\Persistence\Doctrine\Entity\VenueEntity;

final class VenueMapper
{
    public static function toDomain(VenueEntity $venueEntity): Venue
    {
        return Venue::create(
            $venueEntity->id,
            $venueEntity->name,
            $venueEntity->address,
            $venueEntity->city,
            $venueEntity->country,
            new Capacity($venueEntity->capacity),
            $venueEntity->zipCode,
            $venueEntity->latitude,
            $venueEntity->longitude,
        );
    }

    public static function toEntity(Venue $venue): VenueEntity
    {
        $venueEntity = new VenueEntity();

        $venueEntity->id = $venue->id();
        $venueEntity->name = $venue->name();
        $venueEntity->address = $venue->address();
        $venueEntity->city = $venue->city();
        $venueEntity->country = $venue->country();
        $venueEntity->capacity = $venue->capacity()->value;
        $venueEntity->zipCode = $venue->zipCode() ?? null;
        $venueEntity->latitude = $venue->latitude() ?? null;
        $venueEntity->longitude = $venue->longitude() ?? null;

        $venueEntity->createdAt = new \DateTimeImmutable();
        $venueEntity->updatedAt = new \DateTimeImmutable();

        return $venueEntity;
    }
}
