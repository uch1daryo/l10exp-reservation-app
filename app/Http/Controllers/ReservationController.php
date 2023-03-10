<?php

namespace App\Http\Controllers;

use App\Events\ReservationRegisteredEvent;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Resources\ReservationCollection;
use App\Models\Facility;
use App\Models\Reservation;
use App\Usecases\ReservationStoreAction;
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

    public function store(ReservationStoreRequest $request, string $id, ReservationStoreAction $action): RedirectResponse
    {
        $reservation = $request->makeReservation($id);
        $action($reservation);

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

    public function cancel(Request $request, string $id, string $code): View
    {
        $facility = Facility::findOrFail($id);
        $reservation = Reservation::where('cancel_code', $code)->firstOrFail();
        return view('reservations.cancel', compact('facility', 'reservation'));
    }

    public function destroy(Request $request, string $id, string $code): RedirectResponse
    {
        $reservation = Reservation::where('cancel_code', $code)->firstOrFail();
        $reservation->delete();

        return redirect('/facilities/' . $id . '/reservations')->with(
            'status',
            'キャンセルが完了しました'
        );
    }
}
