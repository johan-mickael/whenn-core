<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Domain\Tenant\Tenant;
use App\Domain\User\Role;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    public const BUYER_USER = 'user-buyer';
    public const ADMIN_USER = 'user-admin';
    public const STAFF_USER = 'user-staff';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $tenant = $this->getReference(TenantFixture::ACME_TENANT, Tenant::class);

        foreach ($this->users() as [$ref, $email, $role]) {
            $user = new User(
                tenant: $tenant,
                email: new Email($email),
                passwordHash: '',
                role: $role,
            );
            $user->setPasswordHash($this->hasher->hashPassword($user, 'secret123'));
            $manager->persist($user);
            $this->addReference($ref, $user);
        }

        $manager->flush();
    }

    private function users(): array
    {
        return [
            [self::BUYER_USER, 'buyer@acme.com', Role::BUYER],
            [self::ADMIN_USER, 'admin@acme.com', Role::ADMIN],
            [self::STAFF_USER, 'staff@acme.com', Role::STAFF],
        ];
    }

    public function getDependencies(): array
    {
        return [TenantFixture::class];
    }
}