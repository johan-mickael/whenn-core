<?php

declare(strict_types=1);

namespace App\Application\Auth\Query;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\PlainPassword;

final readonly class AuthenticateUser
{
    public Email $email;
    public PlainPassword $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = Email::create($email);
        $this->password = new PlainPassword($password);
    }
}
