<?php
 
declare(strict_types=1);
 
namespace App\Domain\Ticket;
 
enum TicketStatus: string
{
    case VALID      = 'VALID';
    case CHECKED_IN = 'CHECKED_IN';
    case CANCELLED  = 'CANCELLED';
    case EXPIRED    = 'EXPIRED';
}