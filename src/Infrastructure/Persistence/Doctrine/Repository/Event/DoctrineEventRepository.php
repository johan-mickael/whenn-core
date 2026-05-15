<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventRepositoryInterface;
use App\Domain\Event\Exception\EventNotFound;
use App\Domain\Event\ValueObject\EventId;
use App\Infrastructure\Persistence\Doctrine\Entity\EventEntity;
use App\Infrastructure\Persistence\Doctrine\Mapper\EventMapper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineEventRepository implements EventRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventMapper $eventMapper,
    ) {
    }

    public function create(Event $event): void
    {
        $this->entityManager->persist($this->eventMapper->toEntity($event));
    }

    public function getById(EventId $id): Event
    {
        $eventEntity = $this->entityManager->getRepository(EventEntity::class)->find($id);

        if (is_null($eventEntity)) {
            throw new EventNotFound();
        }

        return $this->eventMapper->toDomain($eventEntity);
    }

    public function remove(Event $event): void
    {
        $this->entityManager->remove($event);
    }

    public function list(): array
    {
        $eventEntities = $this->entityManager->getRepository(EventEntity::class)->findAll();

        return array_map(static fn(EventEntity $eventEntity) => $eventEntity->toDomain($eventEntities), $eventEntities);
    }
}
