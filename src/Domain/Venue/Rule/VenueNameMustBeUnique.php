<?php

namespace App\Domain\Venue\Rule;

use App\Domain\Venue\Exception\DuplicateVenueName;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;

class VenueNameMustBeUnique
{
    private VenueRepositoryInterface $venueRepository;

    public function __construct(VenueRepositoryInterface $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    public function check(Venue $venue): void
    {
        if (empty($this->venueRepository->findByName($venue->name()))) {
            return;
        }

        throw DuplicateVenueName::for($venue->name());
    }
}
