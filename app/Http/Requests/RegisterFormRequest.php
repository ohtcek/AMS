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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //return [
            'over_name' => 'required|min:3',
        ];
    }
    public function messages()
    {
        return [
            'over_name.required' => '入力は必須です。',
            'over_name.min' => '文字数は3文字以内です。',
        ];
    }
}
