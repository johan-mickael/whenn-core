<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Venue;

use App\Application\Venue\Command\CreateVenue;
use App\Application\Venue\CommandHandler\CreateVenueHandler;
use App\UI\Http\Controller\HttpController;
use App\UI\Http\Request\Venue\CreateVenueRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/venues', methods: ['POST'])]
final
class CreateVenueController extends HttpController {
    public
    function __construct(
        private readonly CreateVenueHandler $handler,
    ) {}

    public
    function __invoke(
        #[MapRequestPayload] CreateVenueRequest $dto,
    ): JsonResponse {
        $createVenueCommand = new CreateVenue(
            name: $dto->name,
            address: $dto->address,
            city: $dto->city,
            country: $dto->country,
            capacity: $dto->capacity,
            zipCode: $dto->zipCode,
            latitude: $dto->latitude,
            longitude: $dto->longitude,
        );

        $venue = ($this->handler)(
            $createVenueCommand,
            $this->getUserContext(),
        );

        return $this->json(
            [
                'id'        => $venue->id(),
                'name'      => $venue->name(),
                'address'   => $venue->address(),
                'city'      => $venue->city(),
                'country'   => $venue->country(),
                'capacity'  => $venue->capacity()->value,
                'zipCode'   => $venue->zipCode(),
                'latitude'  => $venue->latitude(),
                'longitude' => $venue->longitude(),
            ],
            Response::HTTP_CREATED,
        );
    }
}
