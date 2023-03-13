<?php

namespace App\Usecases;

use App\Exceptions\DoubleBookingException;
use App\Exceptions\InvalidTimeBookingException;
use App\Exceptions\BanTimeBookingException;
use App\Models\Reservation;
use App\Models\Slot;

class ReservationStoreAction
{
    public function __invoke(Reservation $reservation): void
    {
        assert(!$reservation->exists());

        if ($this->existsOverlap($reservation)) {
            throw new DoubleBookingException;
        }
        if ($this->isUnavailableTimeBooking($reservation)) {
            throw new InvalidTimeBookingException;
        }
        if ($this->isBanTimeBooking($reservation)) {
            throw new BanTimeBookingException;
        }

        $reservation->save();
    }

    private function existsOverlap(Reservation $reservation): bool
    {
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
            return true;
        } else {
            return false;
        }
    }

    private function isUnavailableTimeBooking(Reservation $reservation): bool
    {
        $startDay = date('Y-m-d', strtotime($reservation->start_at));
        $endDay = date('Y-m-d', strtotime($reservation->end_at));
        if ($startDay !== $endDay)
            return true;

        $slot = Slot::where('date', $startDay)->first();
        if ($slot === null)
            return true;

        $startTime = date('H:i:s', strtotime($reservation->start_at));
        $endTime = date('H:i:s', strtotime($reservation->end_at));
        if ($slot->start_at > $startTime)
            return true;
        if ($slot->end_at < $endTime)
            return true;

        return false;
    }

    private function isBanTimeBooking(Reservation $reservation): bool
    {
        $startDay = date('Y-m-d', strtotime($reservation->start_at));
        $slot = Slot::where('date', $startDay)->first();
        if ($slot === null)
            return true;

        $startTime = date('H:i:s', strtotime($reservation->start_at));
        $endTime = date('H:i:s', strtotime($reservation->end_at));

        if ($startTime <= $slot->ban_start_at && $slot->ban_end_at <= $endTime)
            return true;

        if ($startTime < $slot->ban_start_at && $slot->ban_start_at < $endTime)
            return true;

        if ($startTime < $slot->ban_end_at && $slot->ban_end_at < $endTime)
            return true;

        return false;
    }
}
