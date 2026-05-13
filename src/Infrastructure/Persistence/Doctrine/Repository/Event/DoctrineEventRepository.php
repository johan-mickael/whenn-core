<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\EventRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\EventEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineEventRepository implements EventRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findById(string $id): ?EventEntity
    {
        return $this->em->find(EventEntity::class, $id);
    }

    public function save(EventEntity $event): void
    {
        $this->em->persist($event);
    }

    public function remove(EventEntity $event): void
    {
        $this->em->remove($event);
    }
}
