<?php

namespace App\Application\Auth\CommandHandler;

use App\Application\Auth\Command\LoginCommand;

interface LoginUseCase
{
    public function __invoke(LoginCommand $loginCommand): string;
}
