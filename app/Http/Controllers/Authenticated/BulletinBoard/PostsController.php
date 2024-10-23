<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
// use App\Http\Requests\PostCreateRequest;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments', 'likes')->get();
        // Postモデルに()内のメソッドを定義したの意味
        // PostテーブルとLikeテーブルの関係なので、1対多のリレーションが必要になる
        $categories = MainCategory::get();
        $sub_categories = SubCategory::get();
        // メインとサブカテゴリーの両方を取得
        $request->input('sub_category_id');
        $like = new Like;
        $post_comment = new Post;

        // キーワード検索の処理
        if (!empty($request->keyword)) {
            $posts = Post::with('user', 'postComments')
                ->where('post_title', 'like', '%' . $request->keyword . '%')
                ->orWhere('post', 'like', '%' . $request->keyword . '%')
                ->get();

            $subCategory = SubCategory::where('sub_category', $request->keyword)->first();
            // キーワード検索の結果を使ってサブカテゴリーを取得する前に記述
            // キーワードをもとにサブカテゴリーを検索する　あった場合は下記
            // firstを使う意味：サブカテゴリー名は1つのキーワードで完全一致するものがあればそれで良いため、最初の1件だけ取得できればそれでいい　カテゴリー名は重複がないため、一致する一つのものがあった時点で検索していい
            // getだと、関連するすべての投稿が検索されてしまう

            // キーワードにサブカテゴリーの名前で検索された時
            if ($subCategory) {
                // キーワードが空ではなかった場合　更に　もし $subCategory が存在する場合
                $subCategoryPosts = Post::with('user', 'postComments')
                    ->whereHas('subCategories', function ($query) use ($subCategory) {
                        $query->where('sub_category_id', $subCategory->id);
                    })
                    ->get();

                // キーワードでの検索結果とサブカテゴリーの一致結果を結合
                $posts = $posts->merge($subCategoryPosts);
            }
        }

        // サブカテゴリーのフィルタリング
        if ($request->sub_category_id) {
            // $subCategoryId = $request->input('sub_category_id');
            $subCategoryId = $request->sub_category_id;
            // リクエストからサブカテゴリーIDを取得する
            $posts = Post::with('user', 'postComments') // 投稿に関連するユーザーやコメントも取得
                ->whereHas('subCategories', function ($query) use ($subCategoryId) {
                    // 中間テーブル経由でサブカテゴリーと一致する投稿を取得// サブカテゴリーIDが一致するものを取得
                    $query->where('sub_category_id', $subCategoryId);
                    // Postにないカラムから検索をかけ、＝中間テーブルのカラムで検索を行いたい
                    // →サブカテゴリーIDが一致するものを取得
                })
                ->get();
            // dd($posts);
        }

        // いいねした投稿のフィルタリング
        else if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
                ->whereIn('id', $likes)
                ->get();
        }

        // 自分の投稿のフィルタリング
        else if ($request->my_posts) {
            $posts = Post::with('user', 'postComments')
                ->where('user_id', Auth::id())
                ->get();
        }

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'sub_categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);

        $post->subCategories()->attach($request->sub_category_id);
        return redirect()->route('post.show');
    }

    public function postEdit(PostFormRequest $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    // メインカテゴリー追加
    public function mainCategoryCreate(Request $request)
    {
        $request->validate(
            [
                'main_category_name' => 'required|max:100|string|unique:main_categories,main_category',
                'main_category_id' => 'required'
                // uniqueルールでは、重複が許されない=同じ名前を入れられない
            ],
            [
                'main_category_name.required' => 'メインカテゴリーは必ず入力してください。',
                'main_category_name.max' => '100文字以内で入力してください。',
                'main_category_name.string' => '文字列で入力してください。',
                'main_category_name.unique' => '同じ名前のカテゴリーは追加できません。',
                'main_category_id.required' => 'メインカテゴリーは必ず入力してください。',
            ],
        );

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブカテゴリー追加
    public function subCategoryCreate(Request $request)
    {
        $request->validate(
            [
                'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category',
                'main_category_id' => 'exists:main_categories,id',
            ],
            [
                'sub_category_name.required' => 'サブカテゴリーは必ず入力してください。',
                'sub_category_name.max' => '100文字以内で入力してください。',
                'sub_category_name.string' => '文字列で入力してください。',
                'sub_category_name.unique' => '同じ名前のカテゴリーは追加できません。',
                'main_category_id.exists' => '登録されているメインカテゴリを選択してください。',
            ]
        );

        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name,
        ]);

        return back();
    }

    public function commentCreate(Request $request)
    {
        $request->validate(
            ['comment' => 'required|string|max:250'],
            [
                'comment.required' => 'コメントは必ず入力してください。',
                'comment.string' => '文字列で入力してください。',
                'comment.max' => '250文字以内で入力してください。',
            ]
        );

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
