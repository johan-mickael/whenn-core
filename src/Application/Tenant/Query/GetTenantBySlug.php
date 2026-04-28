<?php

declare(strict_types=1);

namespace App\Application\Tenant\Query;

use App\Domain\Tenant\ValueObject\Slug;

final readonly class GetTenantBySlug
{
    public Slug $slug;

    public function __construct(
        public string $_slug,
    ) {
        $this->slug = new Slug($_slug);
    }
}
