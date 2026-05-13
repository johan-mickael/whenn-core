<?php

declare(strict_types=1);

namespace App\Domain\Event\ValueObject;

use App\Domain\Event\Exception\InvalidDateRange;
use DateTimeImmutable;

final readonly class DateRange
{
    public function __construct(
        public DateTimeImmutable $startAt,
        public DateTimeImmutable $endAt,
    ) {
        if ($endAt <= $startAt) {
            throw InvalidDateRange::endBeforeStart($startAt, $endAt);
        }
    }

    public function isOngoing(DateTimeImmutable $now = new DateTimeImmutable()): bool
    {
        return $now >= $this->startAt && $now <= $this->endAt;
    }

    public function isInFuture(DateTimeImmutable $now = new DateTimeImmutable()): bool
    {
        return $this->startAt > $now;
    }

    public function isPast(DateTimeImmutable $now = new DateTimeImmutable()): bool
    {
        return $this->endAt < $now;
    }
}
