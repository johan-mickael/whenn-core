<?php

namespace App\Domain\Common\Id;
interface IdInterface
{
    public function __toString(): string;
}
