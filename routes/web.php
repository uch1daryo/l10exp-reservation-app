<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index']);

Route::controller(ReservationController::class)->group(function () {
    Route::get('/facilities/{id}/reservations', 'index');
    Route::get('/facilities/{id}/reservations/create', 'create');
    Route::post('/facilities/{id}/reservations', 'store');
    Route::get('/facilities/{id}/reservations/{code}', 'cancel');
    Route::delete('/facilities/{id}/reservations/{code}', 'destroy');
})->whereNumber('id')->whereAlphaNumeric('code');
