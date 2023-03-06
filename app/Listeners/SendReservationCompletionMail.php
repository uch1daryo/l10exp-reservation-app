<?php

namespace App\Listeners;

use App\Events\ReservationRegisteredEvent;
use App\Mail\ReservationCompletionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationCompletionMail implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(ReservationRegisteredEvent $event): void
    {
        $reservation = $event->getReservation();
        Mail::to($reservation->user_email)->queue(new ReservationCompletionMail($reservation));
    }
}
