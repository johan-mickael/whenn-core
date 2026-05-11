<?php

namespace App\Domain\Venue\Exception;

use DomainException;

class DuplicateVenueAddress extends DomainException
{
    public static function for(string $venueName): self
    {
        return new self(sprintf('Venue address "%s" already exists', $venueName));
    }
}
