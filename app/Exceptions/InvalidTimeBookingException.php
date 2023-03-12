<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvalidTimeBookingException extends Exception
{
    public function render(Request $request): Response
    {
        abort(400);
    }
}
