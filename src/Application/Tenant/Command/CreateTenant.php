<?php

declare(strict_types=1);

namespace App\Application\Tenant\Command;

use App\Domain\Tenant\ValueObject\Slug;

final readonly class CreateTenant
{
    public function __construct(
        public string $name,
        public Slug $slug,
        public ?string $logoUrl = null,
    ) {}
}
