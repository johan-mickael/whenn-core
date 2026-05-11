<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineEventRepository implements EventRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findById(string $id): ?Event
    {
        return $this->em->find(Event::class, $id);
    }

    public function findByTenantAndSlug(string $tenantId, string $slug): ?Event
    {
        return $this->em->getRepository(Event::class)->findOneBy([
            'tenant' => $tenantId,
            'slug' => $slug,
        ]);
    }

    public function findByTenant(string $tenantId): array
    {
        return $this->em->getRepository(Event::class)->findBy(['tenant' => $tenantId]);
    }

    public function save(Event $event): void
    {
        $this->em->persist($event);
    }

    public function remove(Event $event): void
    {
        $this->em->remove($event);
    }
}
