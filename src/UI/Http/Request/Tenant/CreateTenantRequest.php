<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Tenant;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateTenantRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'name is required.')]
        public readonly ?string $name = '',

        #[Assert\NotBlank(message: 'slug is required.')]
        public readonly ?string $slug = '',

        public readonly ?string $logoUrl = null,
    ) {}
}
