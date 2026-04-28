<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Payment\Payment;
use App\Domain\Ticket\Ticket;
use App\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]
#[ORM\Index(name: 'idx_order_user', columns: ['user_id'])]
#[ORM\Index(name: 'idx_order_status', columns: ['status'])]
#[ORM\HasLifecycleCallbacks]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private User $user;

    #[ORM\Column(enumType: OrderStatus::class)]
    private OrderStatus $status;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $totalAmount;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $tickets;

    #[ORM\OneToOne(targetEntity: Payment::class, mappedBy: 'order', cascade: ['persist'])]
    private ?Payment $payment = null;

    public function __construct(
        User $user,
        string $totalAmount,
        string $currency = 'EUR',
        ?string $notes = null,
    ) {
        $this->user = $user;
        $this->totalAmount = $totalAmount;
        $this->currency = $currency;
        $this->notes = $notes;
        $this->status = OrderStatus::PENDING;
        $this->tickets = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markPaid(): void
    {
        $this->status = OrderStatus::PAID;
    }
    public function cancel(): void
    {
        $this->status = OrderStatus::CANCELLED;
    }
    public function refund(): void
    {
        $this->status = OrderStatus::REFUNDED;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function getStatus(): OrderStatus
    {
        return $this->status;
    }
    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function getNotes(): ?string
    {
        return $this->notes;
    }
    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
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
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }
    public function setPayment(Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    /** @return Collection<int, Ticket> */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
}