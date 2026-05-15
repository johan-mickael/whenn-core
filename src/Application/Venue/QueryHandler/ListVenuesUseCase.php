<?php

namespace App\Application\Venue\QueryHandler;

use App\Application\Venue\Query\ListVenuesQuery;
use App\Application\Venue\Result\ListVenueResult;
use App\Domain\Common\Security\Authorization\UserContext;

interface ListVenuesUseCase
{
    /**
     * @return array<ListVenueResult>
     */
    public function __invoke(ListVenuesQuery $query, UserContext $actor): array;
}
