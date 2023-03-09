@extends('layouts.app')

@section('title', 'キャンセル')

@section('content')
<div class="mt-4">
  <form method="POST" action="/facilities/{{ $facility->id }}/reservations/{{ $reservation->cancel_code }}">
    @csrf
    @method('DELETE')
    <div class="mb-3">
      <label for="facility_name" class="form-label">設備名</label>
      <input class="form-control" type="text" value="{{ $facility->name }}" id="facility_name" name="facility_name" readonly>
    </div>
    <div class="mb-3">
      <label for="user_name" class="form-label">氏名</label>
      <input class="form-control" type="text" value="{{ $reservation->user_name }}" id="user_name" name="user_name" readonly>
    </div>
    <div class="mb-3">
      <label for="user_email" class="form-label">メールアドレス</label>
      <input class="form-control" type="email" value="{{ $reservation->user_email }}" id="user_email" name="user_email" readonly>
    </div>
    <div class="mb-3">
      <label for="purpose" class="form-label">利用目的</label>
      <input class="form-control" type="text" value="{{ $reservation->purpose }}" id="purpose" name="purpose" readonly>
    </div>
    <div class="mb-3">
      <label for="start_at" class="form-label">利用開始日時</label>
      <input class="form-control" type="text" value="{{ $reservation->start_at }}" id="start_at" name="start_at" readonly>
    </div>
    <div class="mb-3">
      <label for="end_at" class="form-label">利用終了日時</label>
      <input class="form-control" type="text" value="{{ $reservation->end_at }}" id="end_at" name="end_at" readonly>
    </div>
    <div class="mb-3">
      <label for="note" class="form-label">備考</label>
      <input class="form-control" type="text" value="{{ $reservation->note }}" id="note" name="note" readonly>
    </div>
    <button type="submit" class="btn btn-danger">キャンセルする</button>
  </form>
</div>
@endsection
