<?php

namespace App\Domain\Event\Exception;

class InvalidEventId extends EventException
{
    public static function create(string $eventId): self
    {
        return new self("Invalid event id: $eventId");
    }
}
