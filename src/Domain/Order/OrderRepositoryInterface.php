<?php

declare(strict_types=1);

namespace App\Domain\Order;

interface OrderRepositoryInterface
{
    public function findById(string $id): ?Order;
    /** @return Order[] */
    public function findByUser(string $userId): array;
    public function save(Order $order): void;
    public function remove(Order $order): void;
}