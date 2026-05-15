<?php

declare(strict_types=1);

namespace App\UI\Http\Request\Event;

use App\Domain\Event\ValueObject\EventSlug;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateEventRequest
{
    #[Assert\NotBlank(message: 'name is required.')]
    public ?string $name = null;

    #[Assert\NotBlank(message: 'venue id is required.')]
    #[Assert\Uuid(message: 'venue id must be a valid UUID.')]
    public ?string $venueId = null;

    #[Assert\NotBlank(message: 'slug is required.')]
    #[Assert\Regex(
        pattern: EventSlug::EVENT_SLUG_PATTERN,
        message: 'slug must be lowercase alphanumeric with hyphens only.'
    )]
    public ?string $eventSlug = null;

    #[Assert\NotBlank(message: 'start date is required.')]
    #[Assert\DateTime(format: DateTimeInterface::ATOM, message: 'start date must be a valid ISO 8601 date.')]
    public ?string $startAt = null;

    #[Assert\NotBlank(message: 'end date is required.')]
    #[Assert\DateTime(format: DateTimeInterface::ATOM, message: 'end date must be a valid ISO 8601 date.')]
    public ?string $endAt = null;

    public ?string $description = null;

    #[Assert\Url(message: 'Image url is required.')]
    public ?string $imageUrl = null;
}
