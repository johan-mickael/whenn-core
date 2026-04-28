<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

final class PlainPassword
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters.');
        }

        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    // No __toString() method to prevent accidental exposure of the password
}