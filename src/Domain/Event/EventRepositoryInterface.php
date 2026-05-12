<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventRepositoryInterface
{
    public function findById(string $id): ?Event;
    /** @return Event[] */
    public function save(Event $event): void;
    public function remove(Event $event): void;
}
