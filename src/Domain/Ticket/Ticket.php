<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

use App\Domain\Order\Order;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ticket')]
#[ORM\Index(name: 'idx_ticket_order', columns: ['order_id'])]
#[ORM\Index(name: 'idx_ticket_category', columns: ['category_id'])]
#[ORM\Index(name: 'idx_ticket_qr_code', columns: ['qr_code'])]
#[ORM\HasLifecycleCallbacks]
class Ticket
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'order_id', nullable: false)]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: TicketCategory::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'category_id', nullable: false)]
    private TicketCategory $category;

    #[ORM\Column(enumType: TicketStatus::class)]
    private TicketStatus $status;

    #[ORM\Column(length: 500, unique: true)]
    private string $qrCode;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attendeeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attendeeEmail = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $checkedInAt = null;

    #[ORM\Column(type: 'guid', nullable: true)]
    private ?string $checkedInById = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        Order $order,
        TicketCategory $category,
        string $qrCode,
        ?string $attendeeName = null,
        ?string $attendeeEmail = null,
    ) {
        $this->order = $order;
        $this->category = $category;
        $this->qrCode = $qrCode;
        $this->attendeeName = $attendeeName;
        $this->attendeeEmail = $attendeeEmail;
        $this->status = TicketStatus::VALID;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function checkIn(string $staffUserId): void
    {
        if ($this->status !== TicketStatus::VALID) {
            throw new \DomainException(sprintf('Cannot check in a ticket with status "%s".', $this->status->value));
        }
        $this->status = TicketStatus::CHECKED_IN;
        $this->checkedInAt = new \DateTimeImmutable();
        $this->checkedInById = $staffUserId;
    }

    public function cancel(): void
    {
        $this->status = TicketStatus::CANCELLED;
    }
    public function expire(): void
    {
        $this->status = TicketStatus::EXPIRED;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getOrder(): Order
    {
        return $this->order;
    }
    public function getCategory(): TicketCategory
    {
        return $this->category;
    }
    public function getStatus(): TicketStatus
    {
        return $this->status;
    }
    public function getQrCode(): string
    {
        return $this->qrCode;
    }
    public function getAttendeeName(): ?string
    {
        return $this->attendeeName;
    }
    public function setAttendeeName(?string $attendeeName): static
    {
        $this->attendeeName = $attendeeName;
        return $this;
    }
    public function getAttendeeEmail(): ?string
    {
        return $this->attendeeEmail;
    }
    public function setAttendeeEmail(?string $attendeeEmail): static
    {
        $this->attendeeEmail = $attendeeEmail;
        return $this;
    }
    public function getCheckedInAt(): ?\DateTimeImmutable
    {
        return $this->checkedInAt;
    }
    public function getCheckedInById(): ?string
    {
        return $this->checkedInById;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}