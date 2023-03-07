@extends('layouts.app')

@section('title', '登録')

@section('content')
<div class="mt-4">
  @if ($errors->any())
  <div class="alert alert-danger" role="alert">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  <form method="POST" action="/facilities/{{ $facility->id }}/reservations">
    @csrf
    <div class="mb-3">
      <label for="facility_name" class="form-label">設備名</label>
      <input class="form-control" type="text" value="{{ $facility->name }}" id="facility_name" name="facility_name" readonly>
    </div>
    <div class="mb-3">
      <label for="user_name" class="form-label">氏名</label>
      <input class="form-control" type="text" value="" id="user_name" name="user_name">
    </div>
    <div class="mb-3">
      <label for="user_email" class="form-label">メールアドレス</label>
      <input class="form-control" type="email" value="" id="user_email" name="user_email">
    </div>
    <div class="mb-3">
      <label for="purpose" class="form-label">利用目的</label>
      <input class="form-control" type="text" value="" id="purpose" name="purpose">
    </div>
    <div class="mb-3">
      <label for="start_at" class="form-label">利用開始日時</label>
      <input class="form-control" type="text" value="{{ $period['start'] }}" id="start_at" name="start_at" readonly>
    </div>
    <div class="mb-3">
      <label for="end_at" class="form-label">利用終了日時</label>
      <input class="form-control" type="text" value="{{ $period['end'] }}" id="end_at" name="end_at" readonly>
    </div>
    <div class="mb-3">
      <label for="note" class="form-label">備考</label>
      <input class="form-control" type="text" value="" id="note" name="note">
    </div>
    <button type="submit" class="btn btn-primary">登録する</button>
  </form>
</div>
@endsection
