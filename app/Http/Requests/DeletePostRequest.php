<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Post;

class DeletePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // パラメータのIDからで削除対象のリプライを取得
        $post = Post::find($this->route("id"));
        // if ($reply == null) return false;

        return $post && $this->user()->id == $post->user_id ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:posts,id',
        ];
    }

    // バリデーション対象のデータにリクエストパラメータを追加する
    public function validationData()
    {
        $data = $this->all();
        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        return $data;
    }
}
