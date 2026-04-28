<?php

declare(strict_types=1);

namespace App\Application\Tenant\Query;

final readonly class GetTenantBySlug
{
    public function __construct(
        public string $slug,
    ) {}
}
