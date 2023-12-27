<?php

namespace App\Providers;

use App\Events\OrderCreate;
use App\Listeners\SendOrderConfirmEmail;
use App\Listeners\SetUpOrderDetails;
use App\Listeners\SetUpShipment;
use App\Listeners\UpdateProductQuantity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreate::class => [
            SetUpOrderDetails::class,
            SetUpShipment::class,
            UpdateProductQuantity::class,
            SendOrderConfirmEmail::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
