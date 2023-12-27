<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateProductQuantity
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
        $attributes = $event->attributes;
        $products =  $event->products;;
        foreach ($products as $product) {
            $product->stock -= $attributes['products'][array_search($product->id, array_column($attributes['products'], 'id'))]['quantity'];
            $product->save();
        }
    }
}
