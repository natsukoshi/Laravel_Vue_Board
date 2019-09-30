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
        \Log::channel('single')->debug("delete start");
        \Log::channel('single')->debug("ID:" . $request->id);

        // $request->$validateRule = [
        //     'id' => 'required|exists:replies,id',
        // ];

        // // //todo エラーメッセージの日本語化　下記メソッドにメッセージを渡す
        // $this->validate($request, $validateRule);

        \Log::channel('single')->debug("バリデーション通過");
        $reply = Reply::find($request->id);

        //画像を削除した後に、DBレコードを削除
        if ($reply->attachment_id != null) {
            \Log::channel('single')->debug("画像消去attachment_id:" . $reply->attachment_id);
            $image = Image::find($reply->attachment_id);
            if ($image != null) {

                // $image->deleteImageFile();
                $image->delete();

                if (file_exists(config("IMAGE_SAVE_PATH") . $image->file_name)) {
                    \Log::channel('single')->debug("画像削除完了");
                } else {
                    \Log::channel('single')->debug("画像削除 未　完了");
                }
            }
        }

        $reply->delete();

        \Log::channel('single')->debug("リプライ削除");


        // リソースの削除なので204(No Content)を返す
        return response($reply, 204);
    }


    /**
     * 返信を投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        \Log::channel('single')->debug("replyコントローラのreply呼ばれたよ");
        \Log::channel('single')->debug($request);
        \Log::channel('single')->debug("id:" . $id);
        \Log::channel('single')->debug("parentID" . $id);

        \Log::channel('single')->debug($request->hasFile('img') ? "hasFile" : "No file");
        \Log::channel('single')->debug($request->file('img') ? "hasImage" : "No Image");

        $validateRule = [
            'message' => 'required',
            'title' => 'required | max:255',
            'parentID' => 'required | exists:posts,id',
            'img' => 'image|mimes:jpg,jpeg,png,gif'
        ];

        \Log::channel('single')->debug("replyバリデーション前");

        $this->validate($request, $validateRule);

        \Log::channel('single')->debug("replyバリデーション後");

        $reply = new Reply;
        $reply->message = $request->message;
        $reply->title = $request->title;
        $reply->parent_id = $request->parentID;
        $reply->attachment_id = null;


        //　todoエラーをキャッチする
        // img保存,取得したImageモデルのIDを格納
        if ($request->hasFile('img')) {
            \Log::channel('single')->debug("ファイル保存する前");
            $img = new Image;
            $img->saveImage($request->file('img'));
            $reply->attachment_id = $img->id;
        }

        // $this->validate($id, $validateParentPost);
        // $idの投稿が存在するかのチェック
        $post = Post::find($id);
        \Log::channel('single')->debug($post);

        if (is_null($post)) {
            \Log::channel('single')->debug('ポストの中身はnullです');
            return response($reply, 422);
        }

        Auth::user()->replies()->save($reply);

        \Log::channel('single')->debug("reply保存完了");

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($reply, 201);
    }
}
