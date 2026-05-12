<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\GetVenueById;
use App\Application\Venue\QueryHandler\GetVenueByIdHandler;
use App\UI\Http\Controller\HttpController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/venues/{id}', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class GetVenueController extends HttpController
{
    public function __construct(
        private readonly GetVenueByIdHandler $handler,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $user  = $this->getUser();
        $venue = ($this->handler)(new GetVenueById(
            id: $id,
        ));

        return $this->json([
            'id'        => $venue->id(),
            'name'      => $venue->name(),
            'address'   => $venue->address(),
            'city'      => $venue->city(),
            'country'   => $venue->country(),
            'capacity'  => $venue->capacity(),
            'zipCode'   => $venue->zipCode(),
            'latitude'  => $venue->latitude(),
            'longitude' => $venue->longitude(),
        ]);
    }
}
