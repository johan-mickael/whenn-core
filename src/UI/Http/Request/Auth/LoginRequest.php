<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $tenant_slug,

        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        public readonly string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tenant_slug: $data['tenant_slug'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
        );
    }
}
