<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeletePostRequest;

use App\Post;
use App\Reply;
use App\User;
use App\Image;

class PostController extends Controller
{

    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')
            ->except(['index', 'user', 'detaile']); //指定したメソッドだけ除外
    }

    /**
     * 投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $validateRule = [
            'message' => 'required',
            'title' => 'required|max:255',
            'img' => 'mimes:jpg,jpeg,png,gif'
        ];
        // dd(request()->all());
        //\Log::channel('single')->debug($request->img->extension());

        \Log::channel('single')->debug("バリデーション通過前");


        //todo エラーメッセージの日本語化　下記メソッドにメッセージを渡す
        $this->validate($request, $validateRule);

        \Log::channel('single')->debug("バリデーション通過");

        $post = new Post;
        $post->message = $request->message;
        $post->title = $request->title;
        $post->attachment_id = null;

        \Log::channel('single')->debug($request->hasFile('img') ? "OK" : "NG");
        \Log::channel('single')->debug($request->file('img') ? "OK" : "NG");

        //　todoエラーをキャッチする
        // img保存,取得したImageモデルのIDを格納
        if ($request->hasFile('img')) {
            \Log::channel('single')->debug("ファイル保存する前");
            $img = new Image;
            $img->saveImage($request->file('img'));
            $post->attachment_id = $img->id;
        }

        \Log::channel('single')->debug("POSTをDB保存する前");

        // Auth::user()：現在認証されているユーザの取得
        //    ->posts()：リレーションシップ\Illuminate\Database\Eloquent\Relations\HasManyが返る
        //      ->save(<Model>)：Attach a model instance to the parent model.
        Auth::user()->posts()->save($post);
        \Log::channel('single')->debug("POSTをDB保存した後");


        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($post, 201);
    }

    /**
     * 投稿一覧取得
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Log::channel('single')->debug("index呼ばれたよ");

        $posts = Post::with(['user', 'image'])-> //リレーションシップuser＝投稿者情報も合わせて取得
            orderBy('CREATED_AT', 'desc')->paginate(5);
        // orderBy('CREATED_AT', 'desc')->get();

        \Log::channel('single')->debug("indexでpost取得後");

        return $posts;
    }


    /**
     * ログイン済みのユーザを取得
     * @return App\User or 空文字
     */
    public function user()
    {
        if (Auth::check()) {
            // \Log::channel('single')->debug('ログインしてる');
            return Auth::user();
        } else {
            // \Log::channel('single')->debug('ログインしてない');
            return '';
        }
    }

    /**
     * 指定されたIDの投稿の詳細（投稿と返信）を取得します
     * @return App\Post or 404エラー
     */
    public function detaile(string $id)
    {
        $post = Post::where('id', $id)->with(['user', 'image'])->first();
        // $post = Post::where('id', $id)->with(['user', 'reply.user'])->first();
        $replies = Reply::where('parent_id', $id)->with(['user', 'image'])->paginate(3);

        \Log::channel('single')->debug($post);

        // ??　はNULL合体演算子　前半の式が存在するときその式を、存在しないときは後半の式を返す
        return ['post' => $post, 'replies' => $replies] ?? abort(404);
    }

    /**
     * 投稿と紐づく返信を削除
     * @param DeletePostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeletePostRequest $request, $id)
    {
        $post = Post::where('id', $id)->with(['reply'])->first();

        //画像を削除した後に、DBレコードを削除
        if ($post->attachment_id != null) {
            $image = Image::find($post->attachment_id);
            if ($image != null) {
                $image->delete();
            }
        }

        \Log::channel('single')->debug("リプライ削除");
        foreach ($post->reply as $reply) {
            $reply->delete();
        }
        $post->delete();


        // リソースの削除なので204(No Content)を返す
        return response($post, 204);
    }
}
