<?php 

namespace App\Domain\Sales\Infrastructure\Enums;

enum SaleStatusesEnum:string 
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}