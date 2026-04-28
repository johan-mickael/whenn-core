<?php

declare(strict_types=1);

namespace App\Domain\Tenant;

use App\Domain\Event\Event;
use App\Domain\Tenant\ValueObject\Slug;
use App\Domain\User\User;
use App\Domain\Venue\Venue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tenant')]
#[ORM\HasLifecycleCallbacks]
class Tenant
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(unique: true)]
    private string $slug;

    #[ORM\Column(nullable: true)]
    private ?string $logoUrl = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'tenant', cascade: ['persist'], orphanRemoval: true)]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'tenant', cascade: ['persist'], orphanRemoval: true)]
    private Collection $events;

    #[ORM\OneToMany(targetEntity: Venue::class, mappedBy: 'tenant', cascade: ['persist'], orphanRemoval: true)]
    private Collection $venues;

    public function __construct(string $name, Slug $slug, ?string $logoUrl = null)
    {
        $this->name = $name;
        $this->slug = (string) $slug;
        $this->logoUrl = $logoUrl;
        $this->users = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->venues = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
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
    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }
    public function setLogoUrl(?string $logoUrl): static
    {
        $this->logoUrl = $logoUrl;
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

    /** @return Collection<int, User> */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /** @return Collection<int, Event> */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /** @return Collection<int, Venue> */
    public function getVenues(): Collection
    {
        return $this->venues;
    }
}
