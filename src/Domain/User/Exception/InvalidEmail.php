<?php

namespace App\Domain\User\Exception;

final class InvalidEmail extends UserException
{
    public static function create(string $email): self
    {
        return new self("Invalid email: $email");
    }
}
