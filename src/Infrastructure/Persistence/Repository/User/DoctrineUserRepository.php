<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findById(string $id): ?User
    {
        return $this->em->find(User::class, $id);
    }

    public function findByTenantAndEmail(string $tenantId, string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy([
            'tenant' => $tenantId,
            'email' => $email,
        ]);
    }

    public function findByTenant(string $tenantId): array
    {
        return $this->em->getRepository(User::class)->findBy(['tenant' => $tenantId]);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
