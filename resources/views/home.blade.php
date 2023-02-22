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
        <tr>
          <td>2023年1月1日</td>
          <td>予約アプリを公開しました。設備の予約にお使いください。</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
