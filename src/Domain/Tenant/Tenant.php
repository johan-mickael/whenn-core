<?php

declare(strict_types=1);

namespace App\Domain\Tenant;

use App\Domain\Tenant\ValueObject\Slug;

final class Tenant
{
    private function __construct(
        private string $id,
        private string $name,
        private Slug $slug,
        private ?string $logoUrl = null,
    ) {}

    public static function create(
        string $id,
        string $name,
        Slug $slug,
        ?string $logoUrl = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            slug: $slug,
            logoUrl: $logoUrl,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): Slug
    {
        return $this->slug;
    }

    public function logoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function changeLogo(?string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    public function changeSlug(Slug $slug): void
    {
        $this->slug = $slug;
    }
}
