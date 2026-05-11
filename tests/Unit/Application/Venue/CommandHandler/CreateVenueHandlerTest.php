<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Venue\CommandHandler;

use App\Application\Venue\Command\CreateVenue;
use App\Application\Venue\CommandHandler\CreateVenueHandler;
use App\Domain\Common\TransactionManagerInterface;
use App\Domain\Tenant\Exception\TenantNotFound;
use App\Domain\Tenant\Tenant;
use App\Domain\Tenant\TenantRepositoryInterface;
use App\Domain\Venue\Service\VenueAddressMustBeUnique;
use App\Domain\Venue\VenueRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateVenueHandlerTest extends TestCase
{
    private TenantRepositoryInterface&MockObject $tenants;
    private VenueRepositoryInterface&MockObject $venues;
    private VenueAddressMustBeUnique&MockObject $venueAddressMustBeUnique;
    private TransactionManagerInterface&MockObject $transaction;
    private CreateVenueHandler $handler;

    protected function setUp(): void
    {
        $this->tenants     = $this->createMock(TenantRepositoryInterface::class);
        $this->venues      = $this->createMock(VenueRepositoryInterface::class);
        $this->venueAddressMustBeUnique = $this->createMock(VenueAddressMustBeUnique::class);
        $this->transaction = $this->createMock(TransactionManagerInterface::class);

        $this->handler = new CreateVenueHandler(
            tenants: $this->tenants,
            venues: $this->venues,
            venueAddressMustBeUnique: $this->venueAddressMustBeUnique,
            transaction: $this->transaction,
        );
    }

    public function test_creates_venue_successfully(): void
    {
        $tenant = $this->createMock(Tenant::class);
        $this->tenants->method('findById')->willReturn($tenant);
        $this->venues->expects($this->once())->method('save');
        $this->transaction->expects($this->once())->method('flush');

        $venue = ($this->handler)(new CreateVenue(
            name: 'Grand Hall',
            address: '1 rue de la Paix',
            city: 'Paris',
            country: 'FR',
            capacity: 500,
        ));

        $this->assertSame('Grand Hall', $venue->getName());
        $this->assertSame(500, $venue->getCapacity());
    }

    public function test_throws_when_tenant_not_found(): void
    {
        $this->tenants->method('findById')->willReturn(null);

        $this->expectException(TenantNotFound::class);

        ($this->handler)(new CreateVenue(
            name: 'Grand Hall',
            address: '1 rue de la Paix',
            city: 'Paris',
            country: 'FR',
            capacity: 500,
        ));
    }

    public function test_throws_when_capacity_is_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CreateVenue(
            name: 'Grand Hall',
            address: '1 rue de la Paix',
            city: 'Paris',
            country: 'FR',
            capacity: 0,
        );
    }
}
