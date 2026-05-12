<?php

namespace App\Domain\Venue\Exception;

use App\Domain\Venue\ValueObject\Address;
use DomainException;

class DuplicateVenueAddress extends DomainException
{
    public static function for(Address $address): self
    {
        return new self(sprintf('Venue address "%s" already exists', $address));
    }
}
