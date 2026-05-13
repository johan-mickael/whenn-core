<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

use App\Infrastructure\Persistence\Doctrine\Entity\EventEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ticket_category')]
#[ORM\Index(name: 'idx_ticket_category_event', columns: ['event_id'])]
#[ORM\HasLifecycleCallbacks]
class TicketCategory
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: EventEntity::class, inversedBy: 'ticketCategories')]
    #[ORM\JoinColumn(name: 'event_id', nullable: false, onDelete: 'CASCADE')]
    private EventEntity $event;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $price;

    #[ORM\Column]
    private string $currency;

    #[ORM\Column]
    private int $totalQuantity;

    #[ORM\Column]
    private int $soldQuantity = 0;

    #[ORM\Column]
    private int $maxPerOrder;

    #[ORM\Column]
    private \DateTimeImmutable $saleStartAt;

    #[ORM\Column]
    private \DateTimeImmutable $saleEndAt;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'category', cascade: ['persist'])]
    private Collection $tickets;

    public function __construct(
        EventEntity $event,
        string $name,
        string $price,
        int $totalQuantity,
        \DateTimeImmutable $saleStartAt,
        \DateTimeImmutable $saleEndAt,
        string $currency = 'EUR',
        int $maxPerOrder = 10,
        ?string $description = null,
    ) {
        $this->event = $event;
        $this->name = $name;
        $this->price = $price;
        $this->totalQuantity = $totalQuantity;
        $this->saleStartAt = $saleStartAt;
        $this->saleEndAt = $saleEndAt;
        $this->currency = $currency;
        $this->maxPerOrder = $maxPerOrder;
        $this->description = $description;
        $this->tickets = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function incrementSoldQuantity(int $amount = 1): void
    {
        if ($this->soldQuantity + $amount > $this->totalQuantity) {
            throw new \DomainException('Not enough tickets available.');
        }
        $this->soldQuantity += $amount;
    }

    public function hasAvailability(int $requested = 1): bool
    {
        return ($this->soldQuantity + $requested) <= $this->totalQuantity;
    }

    public function isOnSale(): bool
    {
        $now = new \DateTimeImmutable();
        return $now >= $this->saleStartAt && $now <= $this->saleEndAt;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getEvent(): EventEntity
    {
        return $this->event;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getPrice(): string
    {
        return $this->price;
    }
    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;
        return $this;
    }
    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }
    public function setTotalQuantity(int $totalQuantity): static
    {
        $this->totalQuantity = $totalQuantity;
        return $this;
    }
    public function getSoldQuantity(): int
    {
        return $this->soldQuantity;
    }
    public function getMaxPerOrder(): int
    {
        return $this->maxPerOrder;
    }
    public function setMaxPerOrder(int $maxPerOrder): static
    {
        $this->maxPerOrder = $maxPerOrder;
        return $this;
    }
    public function getSaleStartAt(): \DateTimeImmutable
    {
        return $this->saleStartAt;
    }
    public function setSaleStartAt(\DateTimeImmutable $saleStartAt): static
    {
        $this->saleStartAt = $saleStartAt;
        return $this;
    }
    public function getSaleEndAt(): \DateTimeImmutable
    {
        return $this->saleEndAt;
    }
    public function setSaleEndAt(\DateTimeImmutable $saleEndAt): static
    {
        $this->saleEndAt = $saleEndAt;
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

    /** @return Collection<int, Ticket> */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
}
