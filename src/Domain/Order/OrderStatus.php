<?php
 
declare(strict_types=1);
 
namespace App\Domain\Order;
 
enum OrderStatus: string
{
    case PENDING   = 'PENDING';
    case PAID      = 'PAID';
    case CANCELLED = 'CANCELLED';
    case REFUNDED  = 'REFUNDED';
}
 