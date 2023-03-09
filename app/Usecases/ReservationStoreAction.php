<?php

namespace App\Usecases;

use App\Exceptions\DoubleBookingException;
use App\Models\Reservation;

class ReservationStoreAction
{
    public function __invoke(Reservation $reservation): void
    {
        assert(!$reservation->exists());

        $allOverlap = Reservation::where('facility_id', $reservation->facility_id)
            ->where('start_at', '>=', $reservation->start_at)
            ->Where('end_at', '<=', $reservation->end_at)
            ->exists();
        $startOverlap = Reservation::where('facility_id', $reservation->facility_id)
            ->where('start_at', '>', $reservation->start_at)
            ->Where('start_at', '<', $reservation->end_at)
            ->exists();
        $endOverlap = Reservation::where('facility_id', $reservation->facility_id)
            ->where('end_at', '>', $reservation->start_at)
            ->Where('end_at', '<', $reservation->end_at)
            ->exists();
        if ($allOverlap || $startOverlap || $endOverlap) {
            throw new DoubleBookingException;
        }

        $reservation->save();
    }
}
