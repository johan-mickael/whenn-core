<?php

declare(strict_types=1);

namespace App\Domain\Common\Transaction;

interface TransactionManagerInterface
{
    public function flush(): void;
}
