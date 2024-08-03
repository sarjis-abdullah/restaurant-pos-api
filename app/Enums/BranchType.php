<?php

namespace App\Enums;

enum BranchType : string
{
    case single        = 'single';
    case multiple      = 'multiple';
    case warehouse      = 'warehouse';
    case generic      = 'generic';
}
