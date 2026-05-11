<?php

namespace App\Domain\User\Exception;

final class InvalidRole extends UserException
{
    public static function create(string $field): self
    {
        return new self();
    }
}
