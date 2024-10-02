<?php

namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers
{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects)
  {
    if (is_null($gender)) {
      $gender = ['1', '2', '3'];
    } else {
      $gender = array($gender);
    }
    if (is_null($role)) {
      $role = ['1', '2', '3', '4'];
    } else {
      $role = array($role);
    }
    $users = User::with('subjects')
      ->where(function ($q) use ($keyword) {
        $q->Where('over_name', 'like', '%' . $keyword . '%')
          ->orWhere('under_name', 'like', '%' . $keyword . '%')
          ->orWhere('over_name_kana', 'like', '%' . $keyword . '%')
          ->orWhere('under_name_kana', 'like', '%' . $keyword . '%');
      })
      ->where(function ($q) use ($role, $gender) {
        $q->whereIn('sex', $gender)
          ->whereIn('role', $role);
      })
      ->whereHas('subjects', function ($q) use ($subjects) {
        // if (!is_array($subjects)) {
        //   $subjects = array($subjects);  // 配列でない場合、配列に変換　38行目のwhereInは、複数の条件で検索するときに使用するものだが、これを使う場合はarrayで配列に変換しなければいけない
        // }
        $q->whereIn('subjects.id', [1, 2, 3]);
        // 上のif文より、[]内に配列を記述した方がシンプルだったのでこっちにした
      })
      ->orderBy('over_name_kana', $updown)->get();
    return $users;
  }
}
