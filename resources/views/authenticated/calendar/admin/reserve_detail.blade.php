@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center; margin-left: 180px;">
  <div class="w-75 m-auto h-75">
    <p><span>{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}</span><span class="ml-3">{{ $part }}部</span></p>

    <div class="table-about">
      <table class="table">
        <tr class="text-center table-header">
          <th class="w-15 reserve-table">ID</th>
          <th class="w-50 reserve-table">名前</th>
          <th class="w-50 reserve-table">場所</th>
        </tr>
        <tr class="text-center">
          <!-- <td class="w-25"></td> -->
          <!-- <td class="w-25"></td> -->
        </tr>

        @foreach($reserveUsers as $reserve)
        <!-- CalendarsControllerで予約したユーザーの情報を集めた際の変数$reserveUsers -->
        @foreach($reserve->users as $user)
        <tr class="text-center user-info">
          <td class="reserve-user-info">{{ $user->id }}</td>
          <td class="reserve-user-info">{{ $user->over_name }} {{ $user->under_name }}</td>
          <td class="reserve-user-info">リモート</td>
        </tr>
        @endforeach
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
