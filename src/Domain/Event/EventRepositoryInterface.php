<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventRepositoryInterface
{
    public function findById(string $id): ?Event;
    public function findByTenantAndSlug(string $tenantId, string $slug): ?Event;
    /** @return Event[] */
    public function findByTenant(string $tenantId): array;
    public function save(Event $event): void;
    public function remove(Event $event): void;
}