<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\ValueObject;

use App\Domain\User\ValueObject\PlainPassword;
use PHPUnit\Framework\TestCase;

final class PlainPasswordTest extends TestCase
{
    public function test_valid_password_is_created(): void
    {
        $password = new PlainPassword('secret123');

        $this->assertSame('secret123', $password->toString());
    }

    public function test_short_password_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PlainPassword('short');
    }

    public function test_password_has_no_toString(): void
    {
        // PlainPassword ne doit pas être castable en string accidentellement
        $this->assertFalse(method_exists(PlainPassword::class, '__toString'));
    }
}