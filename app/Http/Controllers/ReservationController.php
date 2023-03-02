<?php

namespace App\Http\Controllers;

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
        return view('facilities.index', compact('facility'));
    }

    public function create(string $id): View
    {
        $facility = Facility::findOrFail($id);
        return view('facilities.create', compact('facility'));
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
}
