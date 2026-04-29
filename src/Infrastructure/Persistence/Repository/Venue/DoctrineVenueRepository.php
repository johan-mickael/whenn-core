<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Venue;

use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineVenueRepository implements VenueRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function findById(string $id): ?Venue
    {
        return $this->em->find(Venue::class, $id);
    }

    public function findByIdAndTenant(string $id, string $tenantId): ?Venue
    {
        return $this->em->getRepository(Venue::class)->findOneBy([
            'id'     => $id,
            'tenant' => $tenantId,
        ]);
    }

    public function findByTenant(string $tenantId): array
    {
        return $this->em->getRepository(Venue::class)->findBy(['tenant' => $tenantId]);
    }

    public function save(Venue $venue): void
    {
        $this->em->persist($venue);
    }

    public function remove(Venue $venue): void
    {
        $this->em->remove($venue);
    }
}
