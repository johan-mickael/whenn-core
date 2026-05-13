<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Event\Exception\InvalidEventName;
use App\Domain\Event\Exception\InvalidEventStatusTransition;
use App\Domain\Event\ValueObject\DateRange;
use App\Domain\Event\ValueObject\EventId;
use App\Domain\Event\ValueObject\EventSlug;
use App\Domain\Venue\ValueObject\VenueId;
use DateTimeImmutable;

final class Event
{
    private function __construct(
        private readonly EventId $id,
        private string $name,
        private VenueId $venueId,
        private readonly EventSlug $slug,
        private DateRange $dateRange,
        private EventStatus $status,
        private DateTimeImmutable $createdAt,
        private ?string $description,
        private ?string $imageUrl,
    ) {
        $this->assertValidName($name);
    }

    public static function create(
        EventId $id,
        string $name,
        VenueId $venueId,
        EventSlug $slug,
        DateRange $dateRange,
        EventStatus $status,
        DateTimeImmutable $createdAt,
        ?string $description = null,
        ?string $imageUrl = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            venueId: $venueId,
            slug: $slug,
            dateRange: $dateRange,
            status: $status,
            createdAt: $createdAt,
            description: $description,
            imageUrl: $imageUrl,
        );
    }

    public function publish(): void
    {
        if ($this->status !== EventStatus::DRAFT) {
            throw InvalidEventStatusTransition::cannotPublish($this->status);
        }

        $this->status = EventStatus::PUBLISHED;
    }

    public function cancel(): void
    {
        if ($this->status === EventStatus::CANCELLED) {
            throw InvalidEventStatusTransition::cannotCancel($this->status);
        }

        if ($this->status === EventStatus::ENDED) {
            throw InvalidEventStatusTransition::cannotCancel($this->status);
        }

        $this->status = EventStatus::CANCELLED;
    }

    public function end(): void
    {
        if ($this->status !== EventStatus::PUBLISHED) {
            throw InvalidEventStatusTransition::cannotEnd($this->status);
        }

        $this->status = EventStatus::ENDED;
    }

    public function reschedule(DateRange $dateRange): void
    {
        if ($this->status === EventStatus::CANCELLED) {
            throw InvalidEventStatusTransition::cannotReschedule($this->status);
        }

        if ($this->status === EventStatus::ENDED) {
            throw InvalidEventStatusTransition::cannotReschedule($this->status);
        }

        $this->dateRange = $dateRange;
    }

    public function updateDetails(
        string $name,
        ?string $description,
        ?string $imageUrl,
    ): void {
        $this->assertValidName($name);
        $this->name = $name;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
    }

    public function relocate(VenueId $venueId): void
    {
        if ($this->status === EventStatus::CANCELLED) {
            throw InvalidEventStatusTransition::cannotRelocate($this->status);
        }

        $this->venueId = $venueId;
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function venueId(): VenueId
    {
        return $this->venueId;
    }

    public function slug(): EventSlug
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function dateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function imageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function status(): EventStatus
    {
        return $this->status;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function assertValidName(string $name): void
    {
        if (trim($name) === '') {
            throw InvalidEventName::create();
        }
    }
}
