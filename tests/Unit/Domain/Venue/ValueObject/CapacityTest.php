<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Venue\ValueObject;

use App\Domain\Venue\ValueObject\Capacity;
use PHPUnit\Framework\TestCase;

final class CapacityTest extends TestCase
{
    public function test_valid_capacity_is_created(): void
    {
        $capacity = new Capacity(500);
        $this->assertSame(500, $capacity->value);
    }

    public function test_zero_capacity_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Capacity(0);
    }

    public function test_negative_capacity_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Capacity(-1);
    }
}
