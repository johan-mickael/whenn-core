<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findById(string $id): ?Order
    {
        return $this->em->find(Order::class, $id);
    }

    public function findByUser(string $userId): array
    {
        return $this->em->getRepository(Order::class)->findBy(['user' => $userId]);
    }

    public function save(Order $order): void
    {
        $this->em->persist($order);
    }

    public function remove(Order $order): void
    {
        $this->em->remove($order);
    }
}
