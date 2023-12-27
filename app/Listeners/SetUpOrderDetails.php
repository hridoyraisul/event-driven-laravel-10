<?php

namespace App\Listeners;

use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SetUpOrderDetails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $order = $event->order;
        $attributes = $event->attributes;
        $products =  $event->products;
        $orderDetails = [];
        foreach ($products as $product) {
            $orderDetails[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $attributes['products'][array_search($product->id, array_column($attributes['products'], 'id'))]['quantity'],
                'price' => $product->price,
                'total' => $product->price * $attributes['products'][array_search($product->id, array_column($attributes['products'], 'id'))]['quantity'],
                'created_at' => now(),
            ];
        }
        OrderDetails::query()->insert($orderDetails);
    }
}
