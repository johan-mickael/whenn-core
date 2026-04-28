<?php

declare(strict_types=1);

namespace App\Domain\Venue;

use App\Domain\Event\Event;
use App\Domain\Tenant\Tenant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'venue')]
#[ORM\Index(name: 'idx_venue_tenant', columns: ['tenant_id'])]
#[ORM\HasLifecycleCallbacks]
class Venue
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Tenant::class, inversedBy: 'venues')]
    #[ORM\JoinColumn(name: 'tenant_id', nullable: false)]
    private Tenant $tenant;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $address;

    #[ORM\Column]
    private string $city;

    #[ORM\Column]
    private string $country;

    #[ORM\Column]
    private ?string $zipCode = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private int $capacity;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'venue')]
    private Collection $events;

    public function __construct(
        Tenant $tenant,
        string $name,
        string $address,
        string $city,
        string $country,
        int $capacity,
        ?string $zipCode = null,
        ?float $latitude = null,
        ?float $longitude = null,
    ) {
        $this->tenant = $tenant;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->capacity = $capacity;
        $this->zipCode = $zipCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->events = new ArrayCollection();
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
    public function getTenant(): Tenant
    {
        return $this->tenant;
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
    public function getAddress(): string
    {
        return $this->address;
    }
    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }
    public function getCity(): string
    {
        return $this->city;
    }
    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }
    public function getCountry(): string
    {
        return $this->country;
    }
    public function setCountry(string $country): static
    {
        $this->country = $country;
        return $this;
    }
    public function getCapacity(): int
    {
        return $this->capacity;
    }
    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;
        return $this;
    }
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }
    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;
        return $this;
    }
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }
    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
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

    /** @return Collection<int, Event> */
    public function getEvents(): Collection
    {
        return $this->events;
    }
}