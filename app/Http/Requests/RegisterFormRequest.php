<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $date_time = sprintf('%04d-%02d-%02d', $this->old_year, $this->old_month, $this->old_day);
        // 年月日をsprintf関数を使って結合する→$date_timeに定義する　birthdayがキー
        $this->merge([
            'birthday' => $date_time,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     *
     */
    public function rules()
    {
        return [
            //return [
            'over_name' => 'required|string|min:1|max:10',
            'under_name' => 'required|string|min:1|max:10',
            'over_name_kana' => 'required|string|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|min:1|max:30',
            'under_name_kana' => 'required|string|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u|min:1|max:30',
            'mail_address' => 'required|email|unique:users|min:1|max:100',
            'sex' => 'required|in:1,2,3',
            // 'old_year' => 'required|integer|between:2000,' . date('Y'),
            // 'old_month' => 'required|between:1,12',
            // 'old_day' => 'required|between:1,31',
            'role' => 'required|in:1,2,3,4',
            'password' => 'required|string|min:8|max:30|confirmed',
            // 'birth_date' => 'valid_date',
            'birthday' => 'required|date|after:"2000-01-01"|before:"now"'
        ];
    }

    public function messages()
    {
        return [
            'over_name.required' => '姓は必須項目です。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.min' => '姓は1文字以上で入力してください。',
            'over_name.max' => '姓は10文字以内です。',
            'under_name.required' => '名は必須項目です',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.min' => '名は1文字以上で入力してください。',
            'under_name.max' => '名は10文字以内です。',
            'over_name_kana.required' => 'セイは必須項目です。',
            'over_name_kana.string' => 'セイは文字列で入力してください。',
            'over_name_kana.min' => 'セイは1文字以上で入力してください。',
            'over_name_kana.max' => 'セイは30文字以内です。',
            'over_name_kana.regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u' => 'セイはカタカナで入力してください。',
            'under_name_kana.required' => 'メイは必須項目です。',
            'under_name_kana.string' => 'メイは文字列で入力してください。',
            'under_name_kana.min' => 'メイは1文字以上で入力してください。',
            'under_name_kana.max' => 'メイは30文字以内です。',
            'under_name_kana.regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u' => 'メイはカタカナで入力してください',
            'mail_address.required' => 'メールアドレスは必須です。',
            'mail_address.email' => '正しいメールアドレス形式で入力してください。',
            'mail_address.unique' => '既に使用されているメールアドレスです。',
            'mail_address.min' => 'メールアドレスは1文字以上です',
            'mail_address.max' => 'メールアドレスは100文字以内です',
            'sex.required' => '性別は必須項目です。',
            'sex.in' => '性別は男性または女性またはその他を選択してください。',
            'old_year.required' => '年は必須項目です。',
            'old_year.between' => '2000年から現在の年までの間である必要があります。',
            'old_month.required' => '月は必須項目です。',
            'old_month.between' => '月は1から12の間で入力してください。',
            'old_day.required' => '日は必須項目です。',
            'old_day.between' => '日は1から31の間で入力してください。',
            'role.required' => '科目は必須項目です。',
            'role.in' => '科目は国語、数学、英語または生徒を選択してください。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは8文字以上です。',
            'password.max' => 'パスワードは30文字以内です。',
            'password.confirmed' => 'パスワードと確認用パスワードが一致しません。',
            'birthday.required' => '日付は必須項目です。',
            'birthday.date' => '有効な日付を入力してください。',
            'birthday.before' => '過去の日付を入力してください。',
            'birthday.after' => '日付は2000年以降である必要があります。',
        ];
    }
}
