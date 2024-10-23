@extends('layouts.sidebar')
@section('content')
<div class="w-100  d-flex  setting" style="align-items:center; justify-content:center;">
  <div class="calendar-container" style="border-radius:5px;">
    <div class="calendar-about" style="max-width: width: 100%;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      {!! $calendar->render() !!}
      <div class="adjust-table-btn m-auto text-right">
        <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
      </div>
    </div>
  </div>
</div>
@endsection
