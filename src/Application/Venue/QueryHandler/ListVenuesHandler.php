<?php

declare(strict_types=1);

namespace App\Application\Venue\QueryHandler;

use App\Application\Venue\Query\ListVenues;
use App\Domain\Venue\VenueRepositoryInterface;

final class ListVenuesHandler
{
    public function __construct(
       private readonly VenueRepositoryInterface $venueRepository,
    ) {}

    public function __invoke(ListVenues $query): array
    {
        return $this->venueRepository->findByTenant($query->tenantId);
    }
}
