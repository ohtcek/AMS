@extends('layouts.sidebar')

@section('content')
<div class="calendar-container">
  <div class="w-75 m-auto calendar-about">
    <div class="" style="">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <p>{!! $calendar->render() !!}</p>
    </div>
  </div>
</div>
@endsection
