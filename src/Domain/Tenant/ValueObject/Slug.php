<?php

declare(strict_types=1);

namespace App\Domain\Tenant\ValueObject;

final class Slug
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $normalized = mb_strtolower(trim($value));

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $normalized)) {
            throw new \InvalidArgumentException(
                'slug must be lowercase alphanumeric with hyphens only.'
            );
        }

        $this->value = $normalized;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
