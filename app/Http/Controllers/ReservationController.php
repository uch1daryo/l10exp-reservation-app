<?php

namespace App\Http\Controllers;

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

    public function store(Request $request, string $id): RedirectResponse
    {
        $reservation = new Reservation();
        $reservation->facility_id = $id;
        $reservation->user_name = $request->input('user_name');
        $reservation->user_email = $request->input('user_email');
        $reservation->purpose = $request->input('purpose');
        $reservation->start_at = $request->input('start_at');
        $reservation->end_at = $request->input('end_at');
        $reservation->note = $request->input('note');
        $reservation->cancel_code = hash('sha256', spl_object_hash($reservation));;
        $reservation->save();

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
