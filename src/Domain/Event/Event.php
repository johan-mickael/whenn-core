<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Ticket\TicketCategory;
use App\Domain\Venue\Venue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'event')]
#[ORM\Index(name: 'idx_event_status', columns: ['status'])]
#[ORM\HasLifecycleCallbacks]
class Event
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Venue::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'venue_id', nullable: false)]
    private Venue $venue;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(enumType: EventStatus::class)]
    private EventStatus $status;

    #[ORM\Column]
    private \DateTimeImmutable $startAt;

    #[ORM\Column]
    private \DateTimeImmutable $endAt;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: TicketCategory::class, mappedBy: 'event', cascade: ['persist'], orphanRemoval: true)]
    private Collection $ticketCategories;

    public function __construct(
        Venue $venue,
        string $name,
        string $slug,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt,
        ?string $description = null,
        ?string $imageUrl = null,
        EventStatus $status = EventStatus::DRAFT,
    ) {
        $this->venue = $venue;
        $this->name = $name;
        $this->slug = $slug;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->status = $status;
        $this->ticketCategories = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function publish(): void
    {
        $this->status = EventStatus::PUBLISHED;
    }
    public function cancel(): void
    {
        $this->status = EventStatus::CANCELLED;
    }
    public function end(): void
    {
        $this->status = EventStatus::ENDED;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }
    public function setVenue(Venue $venue): static
    {
        $this->venue = $venue;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
    public function getStatus(): EventStatus
    {
        return $this->status;
    }
    public function getStartAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }
    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;
        return $this;
    }
    public function getEndAt(): \DateTimeImmutable
    {
        return $this->endAt;
    }
    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;
        return $this;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @return Collection<int, TicketCategory> */
    public function getTicketCategories(): Collection
    {
        return $this->ticketCategories;
    }
}
