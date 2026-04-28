<?php
 
declare(strict_types=1);
 
namespace App\Domain\Payment;
 
enum PaymentStatus: string
{
    case PENDING   = 'PENDING';
    case SUCCEEDED = 'SUCCEEDED';
    case FAILED    = 'FAILED';
    case REFUNDED  = 'REFUNDED';
}
 