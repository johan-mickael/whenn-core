<?php

namespace App\Domain\Common\Id;

interface IdGeneratorInterface
{
    public function generate(): string;
}
