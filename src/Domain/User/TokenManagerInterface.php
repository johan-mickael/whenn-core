<?php

namespace App\Domain\User;

use App\Domain\User\User;

interface TokenManagerInterface
{
    public function generateFor(User $user): string;
}
