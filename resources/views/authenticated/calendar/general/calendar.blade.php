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

<!-- モーダル -->
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="/post/update" method="post">
      <textarea name="post" class="modal_post"></textarea>
      <input type="hidden" name="id" class="modal_id" value="<?php $post->id ?>">
      <br>
      <input class="update-icon-modal" type="image" value="更新" src="/images/edit.png">
      <!-- <button type=“submit”><img class=“edit-btn” src="/images/edit.png"></button> -->
      {{ csrf_field() }}
    </form>
    <!-- <a class="js-modal-close" href="">閉じる</a> -->
  </div>
</div>
@endsection
