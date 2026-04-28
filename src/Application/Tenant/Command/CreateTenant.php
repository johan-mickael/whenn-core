<?php

declare(strict_types=1);

namespace App\Application\Tenant\Command;

final readonly class CreateTenant
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $logoUrl = null,
    ) {}
}
