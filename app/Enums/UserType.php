<?php

namespace App\Enums;

enum UserType : string
{
    case customer        = 'customer';
    case cashier        = 'cashier';
    case waiter        = 'waiter';
    case manager        = 'manager';
    case chef      = 'chef';
}
