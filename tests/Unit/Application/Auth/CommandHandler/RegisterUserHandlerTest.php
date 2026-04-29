<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Auth\CommandHandler;

use App\Application\Auth\Command\RegisterUser;
use App\Application\Auth\CommandHandler\RegisterUserHandler;
use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\User\Exception\UserAlreadyExists;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterUserHandlerTest extends TestCase
{
    private TenantRepositoryInterface&MockObject $tenants;
    private UserRepositoryInterface&MockObject $users;
    private UserPasswordHasherInterface&MockObject $hasher;
    private TransactionManagerInterface&MockObject $transaction;
    private RegisterUserHandler $handler;

    protected function setUp(): void
    {
        $this->tenants = $this->createMock(TenantRepositoryInterface::class);
        $this->users = $this->createMock(UserRepositoryInterface::class);
        $this->hasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->transaction = $this->createMock(TransactionManagerInterface::class);

        $this->handler = new RegisterUserHandler(
            tenants: $this->tenants,
            users: $this->users,
            hasher: $this->hasher,
            transaction: $this->transaction,
        );
    }

    public function test_registers_new_user_successfully(): void
    {
        $tenant = $this->createMock(Tenant::class);
        $tenant->method('getId')->willReturn('8f3c2b7a-6d1e-4c9f-9a52-3b7e8a1d5c44');

        $this->tenants->method('findBySlug')->willReturn($tenant);
        $this->users->method('findByTenantAndEmail')->willReturn(null);
        $this->hasher->method('hashPassword')->willReturn('hashed_password');
        $this->transaction->expects($this->once())->method('flush');

        $user = ($this->handler)(new RegisterUser(
            tenantSlug: 'acme',
            email: 'buyer@acme.com',
            password: 'secret123',
        ));

        $this->assertSame('buyer@acme.com', $user->getEmailString());
    }

    public function test_throws_when_tenant_not_found(): void
    {
        $this->tenants->method('findBySlug')->willReturn(null);

        $this->expectException(TenantNotFound::class);

        ($this->handler)(new RegisterUser(
            tenantSlug: 'unknown',
            email: 'buyer@acme.com',
            password: 'secret123',
        ));
    }

    public function test_throws_when_email_already_exists(): void
    {
        $tenant = $this->createMock(Tenant::class);
        $existing = $this->createMock(User::class);

        $this->tenants->method('findBySlug')->willReturn($tenant);
        $this->users->method('findByTenantAndEmail')->willReturn($existing);

        $this->expectException(UserAlreadyExists::class);

        ($this->handler)(new RegisterUser(
            tenantSlug: 'acme',
            email: 'buyer@acme.com',
            password: 'secret123',
        ));
    }
}
