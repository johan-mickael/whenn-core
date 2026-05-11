<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'venue')]
#[ORM\HasLifecycleCallbacks]
class VenueEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    public string $id;

    #[ORM\Column]
    public string $name;

    #[ORM\Column]
    public string $address;

    #[ORM\Column]
    public string $city;

    #[ORM\Column]
    public string $country;

    #[ORM\Column(nullable: true)]
    public ?string $zipCode = null;

    #[ORM\Column(nullable: true)]
    public ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    public ?float $longitude = null;

    #[ORM\Column(type: 'integer')]
    public int $capacity;

    #[ORM\Column]
    public \DateTimeImmutable $createdAt;

    #[ORM\Column]
    public \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        if (!isset($this->id)) {
            $this->id = uuid_create(UUID_TYPE_RANDOM);
        }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
