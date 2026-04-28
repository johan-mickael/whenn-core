<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Order\Order;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment')]
#[ORM\Index(name: 'idx_payment_provider_ref', columns: ['provider_ref'])]
#[ORM\HasLifecycleCallbacks]
class Payment
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\OneToOne(targetEntity: Order::class, inversedBy: 'payment')]
    #[ORM\JoinColumn(name: 'order_id', nullable: false, unique: true)]
    private Order $order;

    #[ORM\Column(enumType: PaymentProvider::class)]
    private PaymentProvider $provider;

    #[ORM\Column(nullable: true)]
    private ?string $providerRef = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private PaymentStatus $status;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $failedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $refundedAt = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $metadata = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        Order $order,
        string $amount,
        string $currency = 'EUR',
        PaymentProvider $provider = PaymentProvider::STRIPE,
    ) {
        $this->order = $order;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->provider = $provider;
        $this->status = PaymentStatus::PENDING;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markSucceeded(string $providerRef, ?array $metadata = null): void
    {
        $this->status = PaymentStatus::SUCCEEDED;
        $this->providerRef = $providerRef;
        $this->paidAt = new \DateTimeImmutable();
        $this->metadata = $metadata;
    }

    public function markFailed(?array $metadata = null): void
    {
        $this->status = PaymentStatus::FAILED;
        $this->failedAt = new \DateTimeImmutable();
        $this->metadata = $metadata;
    }

    public function markRefunded(?array $metadata = null): void
    {
        $this->status = PaymentStatus::REFUNDED;
        $this->refundedAt = new \DateTimeImmutable();
        $this->metadata = $metadata;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getOrder(): Order
    {
        return $this->order;
    }
    public function getProvider(): PaymentProvider
    {
        return $this->provider;
    }
    public function getProviderRef(): ?string
    {
        return $this->providerRef;
    }
    public function setProviderRef(?string $providerRef): static
    {
        $this->providerRef = $providerRef;
        return $this;
    }
    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }
    public function getAmount(): string
    {
        return $this->amount;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }
    public function getFailedAt(): ?\DateTimeImmutable
    {
        return $this->failedAt;
    }
    public function getRefundedAt(): ?\DateTimeImmutable
    {
        return $this->refundedAt;
    }
    public function getMetadata(): ?array
    {
        return $this->metadata;
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