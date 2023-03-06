<p>{{ $reservation->user_name }} 様</p>
<br>
<p>予約が完了しました。</p>
<br>
<p>利用開始時間 {{ $reservation->start_at }}</p>
<p>利用終了時間 {{ $reservation->end_at }}</p>
<br>
<p>予約のキャンセルは <a href="{{ config('app.url') }}/facilities/{{ $reservation->facility_id }}/reservations/{{ $reservation->cancel_code }}">こちら</a> から。</p>
