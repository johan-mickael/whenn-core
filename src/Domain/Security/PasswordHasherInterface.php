<?php

namespace App\Domain\Security;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;

    public function verify(string $hashedPassword, string $plainPassword): bool;
}
