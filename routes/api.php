<?php

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/facilities/{facility_id}', function (int $facility_id) {
    $reservations = Facility::findOrFail($facility_id)->reservations;
    return $reservations->toJson();
});
