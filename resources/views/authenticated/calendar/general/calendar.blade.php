@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>

<div id="cancelModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>予約日: <span id="reservationDate"></span></p>
    <p>予約時間: <span id="reservationTime"></span></p>
    <button id="confirmCancel" class="btn btn-danger">キャンセルを確定</button>
  </div>
</div>

@endsection
