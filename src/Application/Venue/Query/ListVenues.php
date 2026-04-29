<?php

declare(strict_types=1);

namespace App\Application\Venue\Query;

final readonly class ListVenues
{
    public function __construct(
        public string $tenantId,
    ) {}
}
