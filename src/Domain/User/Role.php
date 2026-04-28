<?php
 
declare(strict_types=1);
 
namespace App\Domain\User;
 
enum Role: string
{
    case ADMIN = 'ADMIN';
    case STAFF = 'STAFF';
    case BUYER = 'BUYER';
}
 