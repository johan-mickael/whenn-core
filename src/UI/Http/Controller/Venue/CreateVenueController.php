<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Command\CreateVenue;
use App\Application\Venue\CommandHandler\CreateVenueHandler;
use App\UI\Http\Request\Venue\CreateVenueRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/venues', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
final class CreateVenueController extends AbstractController
{
    public function __construct(
        private readonly CreateVenueHandler $handler,
    ) {}

    public function __invoke(
        #[MapRequestPayload] CreateVenueRequest $dto,
    ): JsonResponse {
        $user  = $this->getUser();
        $venue = ($this->handler)(new CreateVenue(
            tenantId: $user->getTenant()->getId(),
            name: $dto->name,
            address: $dto->address,
            city: $dto->city,
            country: $dto->country,
            capacity: $dto->capacity,
            zipCode: $dto->zipCode,
            latitude: $dto->latitude,
            longitude: $dto->longitude,
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
        ], Response::HTTP_CREATED);
    }
}
