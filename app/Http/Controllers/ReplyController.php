<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\DeleteReply;

use App\Reply;
use App\Image;
use App\Post;


class ReplyController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth');
    }

    /**
     * 返信削除
     * @param App\Http\Requests\DeleteReply $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteReply $request, $id)
    {
        $reply = Reply::find($request->id);
        $reply->delete();

        // リソースの削除なので204(No Content)を返す
        return response($reply, 204);
    }


    /**
     * 返信を投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Http\Requests\CreateReplyRequest $request, $id)
    {
        $reply = new Reply;
        $reply->message = $request->message;
        $reply->title = $request->title;
        $reply->parent_id = $request->parentID;
        $reply->attachment_id = null;

        // img保存,取得したImageモデルのIDを格納
        if ($request->hasFile('img')) {
            \Log::channel('single')->debug("ファイル保存する前");
            $img = new Image;
            $img->saveImage($request->file('img'));
            $reply->attachment_id = $img->id;
        }

        Auth::user()->replies()->save($reply);

        \Log::channel('single')->debug("reply保存完了");

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($reply, 201);
    }
}
