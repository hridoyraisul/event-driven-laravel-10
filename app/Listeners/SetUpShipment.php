<?php

namespace App\Listeners;

use App\Models\Shipment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetUpShipment
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

        Shipment::query()->create([
            'order_id' => $order->id,
            'tracking_number' => Str::random(10),
            'phone' => $attributes['phone'],
            'address' => $attributes['address'],
            'email' => $attributes['email'],
        ]);
    }
}
