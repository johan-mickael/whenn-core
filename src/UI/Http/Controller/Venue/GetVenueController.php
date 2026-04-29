<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Query\GetVenueById;
use App\Application\Venue\QueryHandler\GetVenueByIdHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/venues/{id}', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class GetVenueController extends AbstractController
{
    public function __construct(
        private readonly GetVenueByIdHandler $handler,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $user  = $this->getUser();
        $venue = ($this->handler)(new GetVenueById(
            id: $id,
            tenantId: $user->getTenant()->getId(),
        ));

        return $this->json([
            'id'        => $venue->getId(),
            'name'      => $venue->getName(),
            'address'   => $venue->getAddress(),
            'city'      => $venue->getCity(),
            'country'   => $venue->getCountry(),
            'capacity'  => $venue->getCapacity(),
            'zipCode'   => $venue->getZipCode(),
            'latitude'  => $venue->getLatitude(),
            'longitude' => $venue->getLongitude(),
        ]);
    }
}
