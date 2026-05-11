<?php

namespace App\Domain\User;

use App\Domain\User\Exception\InvalidName;
use App\Domain\User\Exception\InvalidTenantId;
use App\Domain\User\ValueObject\Email;
use DateTimeImmutable;

final class User
{
    private function __construct(
        private string            $id,
        private string            $tenantId,
        private Email             $email,
        private string            $passwordHash,
        private DateTimeImmutable $createdAt,
        private Role              $role,
        private ?string           $firstName = null,
        private ?string           $lastName = null,
    )
    {
    }

    public static function create(
        string             $id,
        string             $tenantId,
        Email              $email,
        string             $passwordHash,
        \DateTimeImmutable $registeredAt,
        Role               $role = Role::BUYER,
        ?string            $firstName = null,
        ?string            $lastName = null,
    ): self
    {

        self::assertTenantId($tenantId);
        self::assertName($firstName, 'first_name');
        self::assertName($lastName, 'last_name');

        return new self(
            $id,
            $tenantId,
            $email,
            $passwordHash,
            $registeredAt,
            $role,
            $firstName,
            $lastName,
        );
    }

    private static function assertTenantId(?string $tenantId): void
    {
        if ($tenantId === '') {
            throw InvalidTenantId::create($tenantId);
        }
    }

    private static function assertName(?string $name, ?string $field): void
    {
        if ($name === '' || is_null($name)) {
            throw InvalidName::create($field);
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function tenantId(): string
    {
        return $this->tenantId;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
