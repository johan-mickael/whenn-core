<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Venue\Service;

use App\Domain\Venue\Exception\DuplicateVenueAddress;
use App\Domain\Venue\Service\VenueAddressMustBeUnique;
use App\Domain\Venue\Venue;
use App\Domain\Venue\VenueRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class VenueAddressMustBeUniqueTest extends TestCase
{
    private VenueRepositoryInterface&MockObject $venues;
    private VenueAddressMustBeUnique $service;

    protected function setUp(): void
    {
        $this->venues = $this->createMock(VenueRepositoryInterface::class);

        $this->service = new VenueAddressMustBeUnique(
            $this->venues,
        );
    }

    public function test_passes_when_address_is_unique(): void
    {
        $venue = $this->createMock(Venue::class);
        $venue->method('getAddress')->willReturn('1 rue de paris');

        $this->venues
            ->method('findByAddress')
            ->with('1 rue de paris')
            ->willReturn(null);

        $this->service->check($venue);

        $this->assertTrue(true);
    }

    public function test_throws_when_address_already_exists(): void
    {
        $venue = $this->createMock(Venue::class);
        $existing = $this->createMock(Venue::class);

        $venue->method('getAddress')->willReturn('1 rue de paris');

        $this->venues
            ->method('findByAddress')
            ->with('1 rue de paris')
            ->willReturn($existing);

        $this->expectException(DuplicateVenueAddress::class);

        $this->service->check($venue);
    }
}
