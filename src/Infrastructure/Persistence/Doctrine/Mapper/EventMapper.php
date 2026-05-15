<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\Event\Event;
use App\Domain\Event\ValueObject\DateRange;
use App\Domain\Event\ValueObject\EventId;
use App\Domain\Event\ValueObject\EventSlug;
use App\Domain\Venue\ValueObject\VenueId;
use App\Infrastructure\Persistence\Doctrine\Entity\EventEntity;
use App\Infrastructure\Persistence\Doctrine\Entity\VenueEntity;
use App\Infrastructure\Persistence\Doctrine\Mapper\Exception\CannotMapEventException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

final readonly class EventMapper
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function toDomain(EventEntity $eventEntity): Event
    {
        return Event::create(
            EventId::fromString($eventEntity->getId()),
            $eventEntity->getName(),
            VenueId::fromString($eventEntity->getVenue()->id),
            new EventSlug($eventEntity->getSlug()),
            new DateRange(
                $eventEntity->getStartAt(),
                $eventEntity->getEndAt(),
            ),
            $eventEntity->getStatus(),
            $eventEntity->getCreatedAt(),
            $eventEntity->getDescription(),
            $eventEntity->getImageUrl(),
        );
    }

    /**
     * @throws CannotMapEventException
     */
    public function toEntity(Event $event): EventEntity
    {
        try {
            $venueEntity = $this->entityManager->getReference(VenueEntity::class, (string)$event->venueId());
        } catch (ORMException $e) {
            throw new CannotMapEventException($event, $e->getCode(), $e);
        }

        return new EventEntity(
            (string)$event->id(),
            $venueEntity,
            $event->name(),
            (string)$event->slug(),
            $event->dateRange()->startAt,
            $event->dateRange()->endAt,
            $event->status(),
            $event->description(),
            $event->imageUrl(),
        );
    }
}
