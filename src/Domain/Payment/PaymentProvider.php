<?php
 
declare(strict_types=1);
 
namespace App\Domain\Payment;
 
enum PaymentProvider: string
{
    case STRIPE = 'STRIPE';
    case PAYPAL = 'PAYPAL';
}