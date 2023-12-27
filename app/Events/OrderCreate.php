<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $products;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order, public array $attributes)
    {
        $this->products = Product::query()
            ->whereIn('id', array_column($attributes['products'], 'id'))
            ->get();
    }
}
