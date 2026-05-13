<?php

declare(strict_types=1);

namespace App\Domain\Event\ValueObject;

use App\Domain\Event\Exception\InvalidEventSlug;

final readonly class EventSlug
{
    public string $value;

    private const string EVENT_SLUG_PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    public function __construct(string $value)
    {
        $normalized = mb_strtolower(trim($value));

        if (!preg_match(self::EVENT_SLUG_PATTERN, $normalized)) {
            throw InvalidEventSlug::create($value);
        }

        $this->value = $normalized;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
