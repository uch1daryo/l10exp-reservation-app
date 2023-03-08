<?php

namespace App\Http\Controllers;

use App\Events\ReservationRegisteredEvent;
use App\Exceptions\DoubleBookingException;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Resources\ReservationCollection;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(string $id): View
    {
        $facility = Facility::findOrFail($id);
        return view('reservations.index', compact('facility'));
    }

    public function create(Request $request, string $id): View
    {
        $facility = Facility::findOrFail($id);
        $period = [
            'start' => str_replace('T', ' ', $request->start),
            'end' => str_replace('T', ' ', $request->end),
        ];
        return view('reservations.create', compact('facility', 'period'));
    }

    public function store(ReservationStoreRequest $request, string $id): RedirectResponse
    {
        $allOverlap = Reservation::where('facility_id', $id)
            ->where('start_at', '>=', $request->input('start_at'))
            ->Where('end_at', '<=', $request->input('end_at'))
            ->count();

        $startOverlap = Reservation::where('facility_id', $id)
            ->where('start_at', '>', $request->input('start_at'))
            ->Where('start_at', '<', $request->input('end_at'))
            ->count();

        $endOverlap = Reservation::where('facility_id', $id)
            ->where('end_at', '>', $request->input('start_at'))
            ->Where('end_at', '<', $request->input('end_at'))
            ->count();

        if ($allOverlap || $startOverlap || $endOverlap) {
            throw new DoubleBookingException;
        }

        $reservation = $request->makeReservation($id);
        $reservation->save();

        ReservationRegisteredEvent::dispatch($reservation);

        return redirect('/facilities/' . $id . '/reservations')->with(
            'status',
            '登録が完了しました'
        );
    }

    public function reservationsByFacilityId(string $id)
    {
        $facility = Facility::findOrFail($id);
        return new ReservationCollection($facility->reservations);
    }
}
