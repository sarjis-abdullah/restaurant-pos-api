<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ready_for_kitchen = 'ready for kitchen';
    case delivered = 'delivered';
    case received = 'received';
    case processing = 'processing';
    case requested = 'requested';
    case ready = 'ready';
}
