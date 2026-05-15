<?php

namespace App\UI\Http\Controller\Event;

use App\Application\Event\Command\CreateEventCommand;
use App\Application\Event\CommandHandler\CreateEventUseCase;
use App\UI\Http\Controller\HttpController;
use App\UI\Http\Request\Event\CreateEventRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events', methods: ['POST'])]
final class CreateEventController extends HttpController
{
    public function __construct(
        private readonly CreateEventUseCase $createEventUseCase,
    )
    {
    }

    public function __invoke(
        #[MapRequestPayload] CreateEventRequest $createEventRequest,
    ): JsonResponse
    {
        $createEventCommand = CreateEventCommand::create(
            $createEventRequest->name,
            $createEventRequest->venueId,
            $createEventRequest->eventSlug,
            $createEventRequest->startAt,
            $createEventRequest->endAt,
            $createEventRequest->description,
            $createEventRequest->imageUrl,
        );

        $createEventResult = ($this->createEventUseCase)($createEventCommand, $this->getUserContext());

        return $this->json($createEventResult);
    }
}
