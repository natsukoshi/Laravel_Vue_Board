<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Reply;

class DeleteReply extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 削除しようとしたユーザと投稿者のIDが一致した場合のみ削除
     * @return bool
     */
    public function authorize()
    {
        // パラメータのIDからで削除対象のリプライを取得
        $reply = Reply::find($this->route("id"));
        // if ($reply == null) return false;

        return $reply && $this->user()->id == $reply->user_id ? true : false;
        // $this->user()->can()
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:replies,id',
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
