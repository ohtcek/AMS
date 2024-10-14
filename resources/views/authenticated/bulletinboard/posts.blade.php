@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <div class="mr-5">
            <!-- コメント数の表示 -->
            <p class="m-0"><i class="fa fa-comment"></i>
              <span class="comment_counts">{{ $post->postComments->count() }}</span>
              <!-- PostControllerのshowメソッドでpostComments記述、PostモデルでpostCommentsのリレーション -->
            </p>
          </div>
          <div>
            <!-- いいね数の表示 -->
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
              <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
              <!-- Post.php(モデル)で記述のあるlikesメソッドで数を取得してるのをとってきてる -->
            </p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
              <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">
      </div>
      <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">

      <ul>
        @foreach($categories as $category)
        <!-- カテゴリーの繰り返し -->
        <li>
          <p>{{ $category->main_category }}</p>
          <!-- メインカテゴリーの記述 -->
          <ul>
            @foreach($category->subCategories as $sub_category)
            <!-- サブカテゴリーの繰り返し -->
            @if ($sub_category->main_category_id == $category -> id)
            <!-- 該当のメインカテゴリーに属するサブカテゴリーを表示させるif -->
            <p>
              <a href="{{ route('post.show', ['sub_category_id' => $sub_category->id]) }}">
                {{ $sub_category->sub_category }}
              </a>
            </p>
            <!-- サブカテゴリーの記述 -->
            @endif
            @endforeach
          </ul>
        </li>
        @endforeach
      </ul>

    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
