<?php

namespace App\Models\Posts;

use App\Models\Posts\Like;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
        // コメントの数のリレーション
    }

    public function subCategories()
    {
        // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id)
    {
        return Post::with('postComments')->find($post_id)->postComments();
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Posts\Like', 'like_post_id');
    }
    // いいねの数
    // これにより、PostモデルとLikeモデルが1対多の関係（1つの投稿が複数の「いいね」を持つことができる）になるため、$post->likesで「いいね」の数を取得できるようになる
}
