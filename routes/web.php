<?php

use App\Models\Notice;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');
Route::get('/home', function () {
    $notices = Notice::where('published_on', '<=', now())->get();
    return view('home', compact('notices'));
});
