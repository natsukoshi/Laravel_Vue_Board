<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\DeleteReply;

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
}
