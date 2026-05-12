<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidUserId;

final class UserId
{
    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        if (!uuid_is_valid($value)) {
            throw new InvalidUserId($value);
        }

        return new self($value);
    }

    public static function generate(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
