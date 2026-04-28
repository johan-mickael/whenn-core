<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Order\Order;
use App\Domain\Tenant\Tenant;
use App\Domain\User\ValueObject\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'uq_user_tenant_email', columns: ['tenant_id', 'email'])]
#[ORM\Index(name: 'idx_user_tenant', columns: ['tenant_id'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Tenant::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'tenant_id', nullable: false)]
    private Tenant $tenant;

    // Doctrine stocke le string, le domaine travaille avec le Value Object
    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $passwordHash;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(enumType: Role::class)]
    private Role $role;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user', cascade: ['persist'])]
    private Collection $orders;

    public function __construct(
        Tenant $tenant,
        Email $email,
        string $passwordHash,
        Role $role = Role::BUYER,
        ?string $firstName = null,
        ?string $lastName = null,
    ) {
        $this->tenant = $tenant;
        $this->email = (string) $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->orders = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ── UserInterface ──────────────────────────────────────────

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    public function getRoles(): array
    {
        return ['ROLE_' . $this->role->value];
    }
    public function getPassword(): string
    {
        return $this->passwordHash;
    }
    public function eraseCredentials(): void
    {
    }

    // ── Getters / Setters ──────────────────────────────────────

    public function getId(): string
    {
        return $this->id;
    }
    public function getTenant(): Tenant
    {
        return $this->tenant;
    }
    public function getEmail(): Email
    {
        return new Email($this->email);
    }
    public function getEmailString(): string
    {
        return $this->email;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function setPasswordHash(string $hash): static
    {
        $this->passwordHash = $hash;
        return $this;
    }
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function setFirstName(?string $v): static
    {
        $this->firstName = $v;
        return $this;
    }
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    public function setLastName(?string $v): static
    {
        $this->lastName = $v;
        return $this;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function setRole(Role $role): static
    {
        $this->role = $role;
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

    /** @return Collection<int, Order> */
    public function getOrders(): Collection
    {
        return $this->orders;
    }
}