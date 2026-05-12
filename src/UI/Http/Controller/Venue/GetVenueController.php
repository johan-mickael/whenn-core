<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\GetVenueByIdQuery;
use App\Application\Venue\QueryHandler\GetVenueByIdHandler;
use App\UI\Http\Controller\HttpController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/venues/{id}', methods: ['GET'])]
final class GetVenueController extends HttpController
{
    public function __construct(
        private readonly GetVenueByIdHandler $handler,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $venueResult = ($this->handler)(new GetVenueByIdQuery($id), $this->getUserContext());

        return $this->json($venueResult);
    }
}
