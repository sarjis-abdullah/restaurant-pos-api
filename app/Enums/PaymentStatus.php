<?php

namespace App\Enums;

enum PaymentStatus : string
{
    case success        = 'success';
    case failed        = 'failed';
    case declined        = 'declined';
    case pending        = 'pending';
    case paid = 'paid'   ;
    const unpaid = 'unpaid';
    const partial = 'partial';
}
