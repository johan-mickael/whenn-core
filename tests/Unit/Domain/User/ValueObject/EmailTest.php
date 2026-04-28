<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_valid_email_is_created(): void
    {
        $email = new Email('Buyer@ACME.com');

        $this->assertSame('buyer@acme.com', (string) $email);
    }

    public function test_invalid_email_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Email('not-an-email');
    }

    public function test_empty_email_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Email('');
    }

    public function test_two_emails_are_equal(): void
    {
        $a = new Email('buyer@acme.com');
        $b = new Email('BUYER@ACME.COM');

        $this->assertTrue($a->equals($b));
    }
}