<?php

declare(strict_types=1);

namespace App\Application\Venue\QueryHandler;

use App\Application\Venue\Query\ListVenuesQuery;
use App\Application\Venue\Result\ListVenueResult;
use App\Domain\Common\Security\Authorization\Action;
use App\Domain\Common\Security\Authorization\AuthorizationServiceInterface;
use App\Domain\Common\Security\Authorization\UserContext;
use App\Domain\Venue\Exception\ListVenuesForbidden;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;

final readonly class ListVenuesQueryHandler implements ListVenuesUseCase
{
    public function __construct(
        private VenueRepositoryInterface $venueRepository,
        private AuthorizationServiceInterface $authorizationService
    ) {
    }

    public function __invoke(ListVenuesQuery $query, UserContext $actor): array
    {

        if (!$this->authorizationService->authorize($actor, Action::LIST, Venue::class)) {
            throw new ListVenuesForbidden();
        }

        $venues = $this->venueRepository->listVenues();

        return array_map(static fn(Venue $venue): ListVenueResult => new ListVenueResult(
            (string)$venue->id(),
            $venue->name(),
            $venue->address()->street(),
            $venue->address()->city(),
            $venue->address()->country(),
            $venue->address()->zipCode(),
            $venue->capacity()->value,
            $venue->location()->latitude(),
            $venue->location()->longitude(),
        ), $venues);
    }
}
