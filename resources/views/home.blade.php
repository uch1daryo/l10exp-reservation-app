@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<div class="card mt-4">
  <div class="card-header">お知らせ</div>
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th>公開日</th>
          <th>内容</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($notices as $notice)
        <tr>
          <td>{{ date('Y年n月j日', strtotime($notice->published_on)); }}</td>
          <td>{{ $notice->title }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
