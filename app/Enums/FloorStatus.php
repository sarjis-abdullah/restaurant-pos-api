<?php

namespace App\Enums;

enum FloorStatus : string
{
    case booked        = 'booked';
    case open      = 'open';
    case available      = 'available';
}
