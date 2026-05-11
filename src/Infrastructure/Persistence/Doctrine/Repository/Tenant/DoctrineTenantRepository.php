<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Tenant;

use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Entity\TenantEntity;
use App\Infrastructure\Persistence\Doctrine\Mapper\TenantMapper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineTenantRepository implements TenantRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function findById(string $id): ?Tenant
    {
        $entity = $this->em->find(TenantEntity::class, $id);

        return $entity ? TenantMapper::toDomain($entity) : null;
    }

    public function findBySlug(string $slug): ?Tenant
    {
        $entity = $this->em
            ->getRepository(TenantEntity::class)
            ->findOneBy(['slug' => $slug]);

        return $entity ? TenantMapper::toDomain($entity) : null;
    }

    public function save(Tenant $tenant): void
    {
        $this->em->persist(
            TenantMapper::toEntity($tenant)
        );
    }

    public function remove(Tenant $tenant): void
    {
        $entity = TenantMapper::toEntity($tenant);

        $this->em->remove($entity);
    }
}
