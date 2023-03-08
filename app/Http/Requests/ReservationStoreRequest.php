<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => ['required'],
            'user_email' => ['required', 'email'],
            'purpose' => ['required'],
            'start_at' => ['required', 'before:end_at'],
            'end_at' => ['required', 'after:start_at'],
            'note' => ['nullable'],
        ];
    }

    public function makeReservation(string $id): Reservation
    {
        $reservation = new Reservation();

        $reservation->facility_id = $id;
        $reservation->user_name = $this->input('user_name');
        $reservation->user_email = $this->input('user_email');
        $reservation->purpose = $this->input('purpose');
        $reservation->start_at = $this->input('start_at');
        $reservation->end_at = $this->input('end_at');
        $reservation->note = $this->input('note');
        $reservation->cancel_code = hash('sha256', implode([
            $reservation->facility_id,
            $reservation->user_name,
            $reservation->user_email,
            $reservation->purpose,
            $reservation->start_at,
            $reservation->end_at,
            $reservation->note,
        ]));

        return $reservation;
    }
}
