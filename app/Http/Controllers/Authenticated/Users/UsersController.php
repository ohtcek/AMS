<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;

class UsersController extends Controller
{

    public function showUsers(Request $request)
    {
        $keyword = $request->keyword;
        // 左　変数 nameが入る　
        $category = $request->category;
        $updown = $request->updown;
        $gender = $request->sex;
        $role = $request->role;
        // $subjects = null; // ここで検索時の科目を受け取る
        $subjects = $request->subject; // 追加された部分
        // requestの中のsubjectにname属性から引っ張ってくる
        // また、リクエストパラメータとして選択された科目（subject）を配列として受け取る部分
        // $request->subjectから配列形式で科目のIDが送られてくる。そのためwhereInを使う際に、$subjectsを手動で配列に変換する必要がない状態になっている

        $userFactory = new SearchResultFactories();
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);

        $subjects = Subjects::all(); // 選択科目の一覧を取得してビューに渡す処理
        // 絞った検索条件だけだと今の科目のみが選ばれてしまうので、allで上書きして他の科目が追加されても引き抜けるように記述
        return view('authenticated.users.search', compact('users', 'subjects'));
    }

    public function userProfile($id)
    {
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }


    public function userEdit(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->subjects()->sync($request->subjects);
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }
}
