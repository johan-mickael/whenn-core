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
        $venue = $this->venues->findByIdAndTenant($query->id, $query->tenantId)
            ?? throw VenueNotFound::forId($query->id);

        return $venue;
    }
}
