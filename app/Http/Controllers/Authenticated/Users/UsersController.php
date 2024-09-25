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

    public function searchUsers(Request $request)
    // ユーザー検索　科目は一つでも当てはまれば表示されるようにする！
    {
        if ($request->input('category') === 'name') {
            $users = User::where('over_name', 'like', '%' . $request->input('keyword') . '%')
                ->orWhere('under_name', 'like', '%' . $request->input('keyword') . '%')
                ->get();
        } elseif ($request->input('category') === 'id') {
            $users = User::where('id', $request->input('keyword'))->get();
        }

        if ($request->input('subjects')) {
            $selectedSubjects = $request->input('subjects'); // 選択された科目IDを取得

            // いずれかの科目に一致するユーザーを絞り込む
            $users->whereHas('subjects', function ($q) use ($selectedSubjects) {
                $q->whereIn('id', $selectedSubjects);
            });
        }

        $users = $users->get();

        return view('users.index', compact('users'));
    }
}
