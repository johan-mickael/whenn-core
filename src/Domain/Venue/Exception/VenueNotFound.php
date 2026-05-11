<?php

declare(strict_types=1);

namespace App\Domain\Venue\Exception;

use DomainException;

final class VenueNotFound extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("Venue '{$id}' not found.");
    }
}
