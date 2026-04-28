<?php

declare(strict_types=1);

namespace App\Domain\Common;

interface TransactionManagerInterface
{
    public function flush(): void;
}