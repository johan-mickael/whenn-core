<?php

declare(strict_types=1);

namespace App\Domain\Venue\ValueObject;

final class Capacity
{
    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Capacity must be greater than 0.');
        }

        $this->value = $value;
    }
}
