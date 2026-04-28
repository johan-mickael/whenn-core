<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Tenant;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateTenantRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'name is required.')]
        #[Assert\Length(max: 255)]
        public readonly string $name = '',

        #[Assert\NotBlank(message: 'slug is required.')]
        #[Assert\Length(max: 255)]
        #[Assert\Regex(
            pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            message: 'slug must be lowercase alphanumeric with hyphens only.'
        )]
        public readonly string $slug = '',

        public readonly ?string $logoUrl = null,
    ) {}
}
