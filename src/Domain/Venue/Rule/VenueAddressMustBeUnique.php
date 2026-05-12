<?php

namespace App\Domain\Venue\Rule;

use App\Domain\Venue\Exception\DuplicateVenueAddress;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;

class VenueAddressMustBeUnique
{
    private VenueRepositoryInterface $venueRepository;

    public function __construct(VenueRepositoryInterface $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    public function check(Venue $venue): void
    {
        if (empty($this->venueRepository->findByAddress($venue->address()))) {
            return;
        }

        throw DuplicateVenueAddress::for($venue->address());
    }
}
