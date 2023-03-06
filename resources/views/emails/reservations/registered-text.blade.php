{{ $reservation->user_name }} 様

予約が完了しました。

利用開始時間 {{ $reservation->start_at }}
利用終了時間 {{ $reservation->end_at }}

予約のキャンセルは {{ config('app.url') }}/facilities/{{ $reservation->facility_id }}/reservations/{{ $reservation->cancel_code }} から。
