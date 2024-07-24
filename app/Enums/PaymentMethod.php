<?php

namespace App\Enums;

enum PaymentMethod : string
{
    case cash        = 'cash';
    case due        = 'due';
    case bkash      = 'bkash';
    case nagad      = 'nagad';
}
