<?php

declare(strict_types=1);

namespace App\Domain\Venue\Security\Authorization;

use App\Domain\Common\Security\Authorization\Subject;
use App\Domain\Venue\Venue;

final readonly class VenueContext implements Subject
{
    public function __construct(
        public Venue $venue,
    ) {
    }
}
