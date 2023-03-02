<?php

namespace App\Http\Controllers;

use App\Enums\NoticeState;
use App\Models\Notice;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $notices = Notice::where('published_on', '<=', now())
                         ->where('state', NoticeState::Published)
                         ->get();
        return view('home', compact('notices'));
    }
}
