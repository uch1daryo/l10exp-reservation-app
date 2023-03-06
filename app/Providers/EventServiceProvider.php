<?php

namespace App\Providers;

use App\Events\ReservationRegisteredEvent;
use App\Listeners\SendReservationCompletionMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ReservationRegisteredEvent::class => [
            SendReservationCompletionMail::class,
        ],
    ];

    public function boot(): void
    {
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
