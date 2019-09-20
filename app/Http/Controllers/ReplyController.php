<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reply;
use App\Image;


class ReplyController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 返信削除
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        \Log::channel('single')->debug("delete start");
        \Log::channel('single')->debug("ID:" . $request->id);

        // $validateRule = [
        //     'id' => 'required | exists:replies,id',
        // ];

        // //todo エラーメッセージの日本語化　下記メソッドにメッセージを渡す
        // $this->validate($request, $validateRule);

        \Log::channel('single')->debug("バリデーション通過");
        $reply = Reply::find($request->id);

        if ($reply->attachment_id != null) {
            $image = Image::find($request->attachment_id)->delete();
        }

        \Log::channel('single')->debug("画像消去");

        $reply->delete();

        \Log::channel('single')->debug("リプライ削除");


        // リソースの削除なので204(No Content)を返す
        return response($reply, 204);
    }
}
