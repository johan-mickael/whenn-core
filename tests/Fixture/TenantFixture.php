<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Domain\Tenant\Tenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TenantFixture extends Fixture
{
    public const ACME_TENANT = 'tenant-acme';

    public function load(ObjectManager $manager): void
    {
        $tenant = new Tenant(
            name: 'Acme Corp',
            slug: 'acme',
        );

        $manager->persist($tenant);
        $manager->flush();

        $this->addReference(self::ACME_TENANT, $tenant);
    }
}