<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'tenant_slug is required.')]
        public readonly ?string $tenant_slug,

        #[Assert\NotBlank(message: 'email is required.')]
        #[Assert\Email(message: 'Invalid email format.')]
        public readonly ?string $email,

        #[Assert\NotBlank(message: 'password is required.')]
        #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters.')]
        public readonly ?string $password,

        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tenant_slug: $data['tenant_slug'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            first_name: $data['first_name'] ?? null,
            last_name: $data['last_name'] ?? null,
        );
    }
}
