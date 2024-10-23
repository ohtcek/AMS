@extends('layouts.sidebar')
@section('content')
<div class="vh-100 d-flex" style="margin-left: 180px;">
  <div class="mt-5">
    <div class="m-3 detail_container">
      <div class="p-3">
        <div class="detail_inner_head">
          <div style="">
            @if (Auth::user()->id == $post->user_id)
            <!-- ログインユーザー＝ポスト主の時だけ編集のバリデーションが表示される -->
            @if($errors->first('post_title'))
            <span class="error_message" style="display: block; width: 100%;">{{ $errors->first('post_title') }}</span>
            @endif
            @if($errors->first('post_body'))
            <span class="error_message" style="display: block; width: 100%;">{{ $errors->first('post_body') }}</span>
            @endif
            @endif
          </div>
          <div class="d-flex" style="align-items:center; justify-content:center;">
            <div class="category-display-detail">
              @foreach($post->subCategories as $subCategory)
              <span class="sub-category-text">{{ $subCategory->sub_category }}</span>
              @endforeach
            </div>
            @if (Auth::user()->id == $post->user_id)
            <!-- ログインユーザーとポスト主のユーザーが一致した時表示される -->
            <span class="edit-modal-open edit-button" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
            <a class="delete-button" href="{{ route('post.delete', ['id' => $post->id]) }}" onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a>
            @endif
          </div>
        </div>



        <div class="contributor d-flex">
          <p>
            <span>{{ $post->user->over_name }}</span>
            <span>{{ $post->user->under_name }}</span>
            さん
          </p>
          <span class="ml-5">{{ $post->created_at }}</span>
        </div>
        <div class="detail_post_title">{{ $post->post_title }}</div>
        <div class="mt-3 detail_post" style="color: #CACACA;">{{ $post->post }}</div>
      </div>
      <div class="p-3">
        <div class="comment_container">
          <span class="">コメント</span>
          @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
              <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
              <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
            </p>
            <p>{{ $comment->comment }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <!-- コメント機能 -->
  <div class="w-50 p-3">
    <div class="comment_container border m-5">
      <div class="comment_area p-3">
        @if($errors->first('comment'))
        <span class="error_message">{{ $errors->first('comment') }}</span>
        <!-- エラーメッセージ -->
        @endif
        <p class="m-0">コメントする</p>
        <textarea class="w-100" name="comment" form="commentRequest" style="border:1px #CACACA solid;"></textarea>
        <div style="text-align: right;">
          <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
          <input type="submit" class="btn btn-primary" form="commentRequest" value="投稿">
        </div>
        <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
      </div>
    </div>
  </div>
</div>

<div class="modal js-modal">
  <!-- 投稿の編集 -->
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content d-flex" style="align-items:center; justify-content:center;">
    <form action="{{ route('post.edit') }}" method="post">
      <div class="modal-form">
        <div class="modal-inner-title  m-auto">
          <input type="text" name="post_title" placeholder="タイトル" class="post-edit-modal" style="border:1px #CACACA solid;">
        </div>
        <div class="modal-inner-body m-auto pt-3 pb-3">
          <textarea placeholder="投稿内容" name="post_body" class="post-edit-modal" style="border:1px #CACACA solid;"></textarea>
        </div>
        <div class="m-auto edit-modal-btn d-flex" style="justify-content: space-between;">
          <a class="btn btn-danger js-modal-close" style="margin:20px 0 0 0; height:40px" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>
@endsection
