@extends('layouts.sidebar')

@section('content')
<div class="w-100 vh-100 d-flex pt-5 py-5 setting" style="align-items:center; justify-content:center;">
  <div class="calendar-container" style="border-radius:5px;">
    <div class="calendar-about">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      {!! $calendar->render() !!}
      <div class="text-right">
        <input type="submit" class="btn btn-primary reserve-btn" value="予約する" form="reserveParts">
      </div>
    </div>
  </div>
</div>

<!-- モーダル -->
<form action=" {{ route('deleteParts') }}" method="post">
  <div id=" cancelModal" class="modal">
    <div class="modal__content">
      <span class="close cross">&times;</span>
      <p class="reservationDate"></p>
      <input type="hidden" name="getDate" class="hidden-date">
      <!-- 予約日の取得、表示 -->
      <p class="reservationTime"></p>
      <input type="hidden" name="getPart" class="hidden-part">
      <!-- 予約時間の取得、表示　calendarVew.phpのinput buttonの中で送るデータを作ってる jsで定義 -->
      <p>上記の時間をキャンセルしてもよろしいですか？</p>
      <div class="modal-btn">
        <button id="confirmClose" class="btn btn-close">閉じる</button>
        <!-- <button id="confirmCancel" class="btn btn-danger">キャンセル</button> -->
        <input type="submit" class="btn btn-danger" name="" value="キャンセル">
      </div>
    </div>
  </div>
  {{ csrf_field() }}

  @if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

</form>
@endsection
