<?php

namespace App\Models;

use App\Enums\NoticeState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $casts = [
        'state' => NoticeState::class
    ];
}
