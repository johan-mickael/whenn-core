<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Tenant;

use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final readonly class DoctrineTenantRepository implements TenantRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function findById(string $id): ?Tenant
    {
        return $this->em->find(Tenant::class, $id);
    }

    public function findBySlug(string $slug): ?Tenant
    {
        return $this->em->getRepository(Tenant::class)->findOneBy(['slug' => $slug]);
    }

    public function save(Tenant $tenant): void
    {
        $this->em->persist($tenant);
    }

    public function remove(Tenant $tenant): void
    {
        $this->em->remove($tenant);
    }
}
