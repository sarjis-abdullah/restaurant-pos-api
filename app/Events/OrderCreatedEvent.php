<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public array $orderData;
    public array $calculatedOrder;
    public bool $hasDiscounts;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $orderData, $calculatedOrder, $hasDiscounts)
    {
        $this->order = $order;
        $this->orderData = $orderData;
        $this->calculatedOrder = $calculatedOrder;
        $this->hasDiscounts = $hasDiscounts;
    }
}
