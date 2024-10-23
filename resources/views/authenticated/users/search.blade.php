@extends('layouts.sidebar')

@section('content')
<!-- <p>ユーザー検索</p> -->
<div class="search_content w-100 d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div class="m-15 h-15">
        <span style="color:#9A9A9B;">ID : </span><span>{{ $user->id }}</span>
      </div>
      <div style="height:15px; margin-bottom:5px"><span style=" color:#9A9A9B;">名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span style="color:#4CA8CE;">{{ $user->over_name }}</span>
          <span style="color:#4CA8CE;">{{ $user->under_name }}</span>
        </a>
      </div>
      <div class="m-15 h-15">
        <span style="color:#9A9A9B; margin:0">カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div class="m-15 h-15">
        @if($user->sex == 1)
        <span style="color:#9A9A9B;">性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span style="color:#9A9A9B;">性別 : </span><span>女</span>
        @else
        <span style="color:#9A9A9B;">性別 : </span><span>その他</span>
        @endif
      </div>
      <div class="m-15 h-15">
        <span style="color:#9A9A9B;">生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div style="height:15px; margin-bottom:15px;">
        @if($user->role == 1)
        <span style="color:#9A9A9B;">権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span style="color:#9A9A9B;">権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span style="color:#9A9A9B;">権限 : </span><span>講師(英語)</span>
        @else
        <span style="color:#9A9A9B;">権限 : </span><span>生徒</span>
        @endif
      </div>
      <div class="m-15 h-15">
        @if($user->role == 4)
        <span style="color:#9A9A9B;">選択科目 : </span><span>
          @foreach($user->subjects as $subject)
          <!-- $user→userモデル　7行目のusers as userを使ってるから使える
           ？その中のsubjects as $subjectはmリレーションでusersモデルにsubjectsを記述してるから書ける
             usersのforeach内だから使える　　 -->
          <!-- profile.blade.phpの選択科目の箇所と同じ記述で表示できた。同じusers内なので、$users->のsubjectカラムで表示可能 -->
          <span>{{ $subject->subject }}</span>
          @endforeach</span>
        @endif
      </div>
    </div>
    @endforeach
  </div>

  <!-- 検索バー -->
  <div class="search_area w-25 search">
    <div class="">
      <p class="search-title">検索</p>
      <div class="m-15">
        <input type=" text" class="free_word search-form" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="search-category m-15">
        <lavel style="margin-bottom:5px">カテゴリ</lavel>
        <select form="userSearchRequest" name="category" class="search-select">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="search-category">
        <label>並び替え</label>
        <select name="updown" form="userSearchRequest" class="search-select">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>

      <div class="">
        <div class="accordion m-20">
          <p class="m-0 search_conditions"><span>検索条件の追加</span></p>
          <span class="v">⌄</span>
        </div>
        <div class="search_conditions_inner">
          <div class="search-sex m-15">
            <label style="margin-bottom:5px">性別</label>
            <div class="sex-options">
              <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
              <span class="sex-radio">女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
              <span class="sex-radio">その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
          </div>
          <div class="search-role m-15">
            <label style="margin-bottom:5px">権限</label>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="selected_engineer">
            <label style="margin-bottom:5px">選択科目</label>
            <div class="subject-options">
              @foreach($subjects as $subject)
              <div class="search-engineer">
                <label>{{ $subject->subject }}</label>
                <input type="checkbox" name="subject[]" value="{{ $subject->id }}" form="userSearchRequest">
                <!-- databaseにあるすべてを表示させたいからlabelの書き方(性別とは違う書き方) -->
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div>
        <input type="submit" name="search_btn" value="検索" form="userSearchRequest" class="search-button m-25">
      </div>
      <div class="button">
        <input type="reset" value="リセット" form="userSearchRequest" class="reset-button">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
