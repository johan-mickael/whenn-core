<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Tenant\ValueObject;

use App\Domain\Tenant\ValueObject\Slug;
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function test_valid_slug_is_created(): void
    {
        $slug = new Slug('my-tenant-123');
        $this->assertSame('my-tenant-123', $slug->value);
    }

    public function test_slug_is_normalized_to_lowercase(): void
    {
        $slug = new Slug('  MyTenant  ');
        $this->assertSame('mytenant', $slug->value);
    }

    public function test_invalid_slug_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Slug('Invalid Slug!!');
    }

    public function test_slug_with_spaces_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Slug('my tenant');
    }
}
