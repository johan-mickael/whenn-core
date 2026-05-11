<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Venue;

use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\VenueEntity;
use App\Infrastructure\Persistence\Doctrine\Mapper\VenueMapper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineVenueRepository implements VenueRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function listVenues(): array
    {
        $entities = $this->em
            ->getRepository(VenueEntity::class)
            ->findAll();

        return array_map(
            static fn (VenueEntity $entity): Venue => VenueMapper::toDomain($entity),
            $entities
        );
    }

    public function findById(string $id): ?Venue
    {
        $entity = $this->em->find(VenueEntity::class, $id);

        return $entity
            ? VenueMapper::toDomain($entity)
            : null;
    }

    public function save(Venue $venue): void
    {
        $this->em->persist(
            VenueMapper::toEntity($venue)
        );
    }

    public function remove(Venue $venue): void
    {
        $entity = VenueMapper::toEntity($venue);

        $this->em->remove($entity);
    }

    public function findByAddress(string $address): ?Venue
    {
        $entity = $this->em
            ->getRepository(VenueEntity::class)
            ->findOneBy([
                'address' => $address,
            ]);

        return $entity
            ? VenueMapper::toDomain($entity)
            : null;
    }
}
