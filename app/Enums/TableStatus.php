<?php

namespace App\Enums;

enum TableStatus : string
{
    case fillUp        = 'fill-up';
    case empty      = 'empty';
    case available      = 'available';
}