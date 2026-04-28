<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTenantRepository implements TenantRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

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
        $this->em->flush();
    }

    public function remove(Tenant $tenant): void
    {
        $this->em->remove($tenant);
        $this->em->flush();
    }
}
