<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Infrastructure\Persistence\Doctrine\Entity\EventEntity;

interface EventRepositoryInterface
{
    public function findById(string $id): ?EventEntity;
    /** @return EventEntity[] */
    public function save(EventEntity $event): void;
    public function remove(EventEntity $event): void;
}
