<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\ListVenuesQuery;
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
        $venuesResult = ($this->handler)(new ListVenuesQuery(), $this->getUserContext());

        return $this->json($venuesResult);
    }
}
