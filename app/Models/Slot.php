<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_at',
        'end_at',
        'ban_start_at',
        'ban_end_at',
    ];
}
