<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Venue;

use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final class DoctrineVenueRepository implements VenueRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function listVenues(): array
    {
        return $this->em->getRepository(Venue::class)->findAll();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function findById(string $id): ?Venue
    {
        return $this->em->find(Venue::class, $id);
    }

    public function save(Venue $venue): void
    {
        $this->em->persist($venue);
    }

    public function remove(Venue $venue): void
    {
        $this->em->remove($venue);
    }

    public function findByAddress(string $address): ?Venue
    {
        return $this->em->getRepository(Venue::class)->findOneBy([
            'address' => $address,
        ]);
    }
}
