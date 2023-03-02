<?php

use App\Http\Controllers\HomeController;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index']);

Route::get('/facilities/{facility_id}/reservations', function ($facility_id) {
    $facility = Facility::findOrFail($facility_id);
    return view('facilities.index', compact('facility'));
})->whereNumber('facility_id');

Route::get('/facilities/{facility_id}/reservations/create', function ($facility_id) {
    $facility = Facility::findOrFail($facility_id);
    return view('facilities.create', compact('facility'));
})->whereNumber('facility_id');

Route::post('/facilities/{facility_id}/reservations', function ($facility_id, Request $request) {
    $reservation = new Reservation();
    $reservation->facility_id = $facility_id;
    $reservation->user_name = $request->input('user_name');
    $reservation->user_email = $request->input('user_email');
    $reservation->purpose = $request->input('purpose');
    $reservation->start_at = $request->input('start_at');
    $reservation->end_at = $request->input('end_at');
    $reservation->note = $request->input('note');
    $reservation->cancel_code = hash('sha256', spl_object_hash($reservation));;
    $reservation->save();

    return redirect('/facilities/' . $facility_id . '/reservations')->with(
        'status',
        '登録が完了しました'
    );
})->whereNumber('facility_id');
