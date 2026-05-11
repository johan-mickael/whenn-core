<?php

namespace App\Domain\User\Exception;

final class InvalidName extends UserException
{
    public static function create(string $fieldName): self
    {
        return new self("Invalid: '$fieldName'.");
    }
}
