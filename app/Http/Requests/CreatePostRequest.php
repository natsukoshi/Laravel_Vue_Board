<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'message' => 'required',
            'title' => 'required|max:255',
            'img' => 'mimes:jpg,jpeg,png,gif'
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array
     */
    public function messages()
    {
        return [
            'message.required' => 'メッセージを入力してください。',
            'title.required'  => 'タイトルを入力してください。',
            'img.mimes' => '画像はjpg,png,gifのみ投稿可能です。',
        ];
    }
}
