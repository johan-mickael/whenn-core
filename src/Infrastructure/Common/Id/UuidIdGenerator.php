<?php

namespace App\Infrastructure\Common\Id;

use App\Domain\Common\Id\IdGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class UuidIdGenerator implements IdGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
