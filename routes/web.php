<?php

use App\Enums\NoticeState;
use App\Models\Notice;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');

Route::get('/home', function () {
    $notices = Notice::where('published_on', '<=', now())
                     ->where('state', NoticeState::Published)
                     ->get();
    return view('home', compact('notices'));
});
