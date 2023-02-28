<?php

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/facilities/{facility_id}', function (int $facility_id) {
    $reservations = Facility::findOrFail($facility_id)->reservations;
    $events = [];
    foreach ($reservations as $reservation) {
        array_push($events, [
            'id' => $reservation->id,
            'title' => $reservation->purpose,
            'start' => $reservation->start_at,
            'end' => $reservation->end_at,
            'description' => $reservation->user_name,
        ]);
    }
    return response()->json($events);
});
