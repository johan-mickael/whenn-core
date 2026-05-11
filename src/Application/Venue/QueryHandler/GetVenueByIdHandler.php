<?php

declare(strict_types=1);

namespace App\Application\Venue\QueryHandler;

use App\Application\Venue\Query\GetVenueById;
use App\Domain\Venue\Exception\VenueNotFound;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;

final class GetVenueByIdHandler
{
    public function __construct(
        private readonly VenueRepositoryInterface $venues,
    ) {}

    public function __invoke(GetVenueById $query): Venue
    {
        return $this->venues->findById($query->id)
            ?? throw VenueNotFound::forId($query->id);
    }
}
