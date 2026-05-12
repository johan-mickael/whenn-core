<?php

namespace App\Domain\Venue\Exception;

use DomainException;

class DuplicateVenueName extends DomainException
{
    public static function for(string $venueName): self
    {
        return new self(sprintf('Venue name "%s" already exists', $venueName));
    }
}
