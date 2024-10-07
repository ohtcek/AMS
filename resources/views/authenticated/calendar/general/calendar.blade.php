@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
      <div>
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
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
