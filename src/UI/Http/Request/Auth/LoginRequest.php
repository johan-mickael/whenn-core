<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'email is required.')]
        #[Assert\Email]
        public readonly ?string $email,

        #[Assert\NotBlank(message: 'password is required.')]
        public readonly ?string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
        );
    }
}
