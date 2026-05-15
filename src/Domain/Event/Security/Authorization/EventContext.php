<?php

declare(strict_types=1);

namespace App\Domain\Event\Security\Authorization;

use App\Domain\Common\Security\Authorization\Subject;
use App\Domain\Event\Event;

final readonly class EventContext implements Subject
{
    public function __construct(
        public Event $event,
    ) {
    }
}
