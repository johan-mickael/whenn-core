<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\ListVenues;
use App\Application\Venue\QueryHandler\ListVenuesHandler;
use App\UI\Http\Controller\HttpController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/venues', methods: ['GET'])]
final class ListVenuesController extends HttpController
{
    public function __construct(
        private readonly ListVenuesHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $venues = ($this->handler)(new ListVenues);

        return $this->json(array_map(fn($venue) => [
            'id'        => $venue->id(),
            'name'      => $venue->name(),
            'city'      => $venue->city(),
            'country'   => $venue->country(),
            'capacity'  => $venue->capacity()->value,
        ], $venues));
    }
}
