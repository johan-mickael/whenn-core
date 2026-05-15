<?php

declare(strict_types=1);

namespace App\Application\Event\CommandHandler;

use App\Application\Event\Command\CreateEventCommand;
use App\Application\Event\Result\CreateEventResult;
use App\Domain\Common\Id\IdGeneratorInterface;
use App\Domain\Common\Security\Authorization\UserContext;
use App\Domain\Event\Event;
use App\Domain\Event\EventRepositoryInterface;
use App\Domain\Event\ValueObject\DateRange;
use App\Domain\Event\ValueObject\EventId;
use App\Domain\Event\ValueObject\EventSlug;
use App\Domain\Venue\ValueObject\VenueId;
use App\Domain\Venue\VenueRepositoryInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

readonly class CreateEventCommandHandler implements CreateEventUseCase
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private VenueRepositoryInterface $venueRepository,
        private IdGeneratorInterface $idGenerator,
        private ClockInterface $clock,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(CreateEventCommand $command, UserContext $actor): CreateEventResult
    {
        // Check for venue if exists
        $this->venueRepository->getById($command->venueId);

        $event = Event::create(
            EventId::fromString($this->idGenerator->generate()),
            $command->name,
            VenueId::fromString($command->venueId),
            EventSlug::create($command->eventSlug),
            DateRange::create(
                $command->startAt,
                $command->endAt,
            ),
            $this->clock->now(),
        );

        $this->eventRepository->create($event);
        $this->entityManager->flush();

        return new CreateEventResult(
            (string) $event->id(),
            $event->name(),
            (string) $event->venueId(),
            (string) $event->slug(),
            $event->dateRange()->startAt->format(DateTimeInterface::ATOM),
            $event->dateRange()->endAt->format(DateTimeInterface::ATOM),
            $event->status()->value,
            $event->createdAt()->format(DateTimeInterface::ATOM),
            $event->description(),
            $event->imageUrl(),
        );
    }
}
