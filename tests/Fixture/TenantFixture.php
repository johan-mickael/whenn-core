<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Infrastructure\Persistence\Doctrine\Entity\TenantEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TenantFixture extends Fixture
{
    public const ACME_TENANT = 'tenant-acme';

    public function load(ObjectManager $manager): void
    {
        $tenant = new TenantEntity();

        $tenant->id = uuid_create(UUID_TYPE_RANDOM);
        $tenant->name = 'Acme Corp';
        $tenant->slug = 'acme';
        $tenant->logoUrl = null;
        $tenant->createdAt = new \DateTimeImmutable();
        $tenant->updatedAt = new \DateTimeImmutable();

        $manager->persist($tenant);
        $manager->flush();

        $this->addReference(self::ACME_TENANT, $tenant);
    }
}
