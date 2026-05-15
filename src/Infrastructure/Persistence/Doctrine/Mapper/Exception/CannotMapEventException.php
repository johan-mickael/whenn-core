<?php

namespace App\Infrastructure\Persistence\Doctrine\Mapper\Exception;

use App\Domain\Event\Event;
use RuntimeException;
use Throwable;

class CannotMapEventException extends RuntimeException
{
    public function __construct(Event $event, int $code, Throwable $previous)
    {
        parent::__construct(
            sprintf(
                'Cannot map event "%s"',
                $event->id(),
            ),
            $code,
            $previous,
        );
    }
}
