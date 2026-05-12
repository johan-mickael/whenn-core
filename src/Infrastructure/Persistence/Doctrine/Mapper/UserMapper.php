<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Persistence\Doctrine\Entity\UserEntity;

final class UserMapper
{
    public static function toDomain(UserEntity $userEntity): User
    {
        return User::register(
            UserId::fromString($userEntity->id),
            Email::create($userEntity->email),
            $userEntity->passwordHash,
            $userEntity->createdAt,
            $userEntity->role,
            $userEntity->firstName,
            $userEntity->lastName,
        );
    }

    public static function toEntity(User $user): UserEntity
    {
        $userEntity = new UserEntity();

        $userEntity->id = (string) $user->id();
        $userEntity->email = (string) $user->email();
        $userEntity->passwordHash = $user->passwordHash();
        $userEntity->role = $user->role();
        $userEntity->firstName = $user->firstName();
        $userEntity->lastName = $user->lastName();
        $userEntity->createdAt = $user->createdAt();

        return $userEntity;
    }
}
