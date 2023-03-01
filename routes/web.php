<?php

use App\Enums\NoticeState;
use App\Models\Facility;
use App\Models\Notice;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');

Route::get('/home', function () {
    $notices = Notice::where('published_on', '<=', now())
                     ->where('state', NoticeState::Published)
                     ->get();
    return view('home', compact('notices'));
});

Route::get('/facilities/{facility_id}', function ($facility_id) {
    $facility = Facility::findOrFail($facility_id);
    return view('facilities.index', compact('facility'));
})->whereNumber('facility_id');
