<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeletePostRequest;

use App\Post;
use App\Reply;
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
     * @param \App\Http\Requests\CreatePostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Http\Requests\CreatePostRequest $request)
    {
        $post = new Post;
        $post->message = $request->message;
        $post->title = $request->title;
        $post->attachment_id = null;

        //　todoエラーをキャッチする
        // img保存,取得したImageモデルのIDを格納
        if ($request->hasFile('img')) {
            try {
                \Log::channel('errorlog')->debug("ファイル保存する前");
                $img = new Image;
                $img->saveImage($request->file('img'));
            } catch (\Exception $e) {
                return response(500);
            }
            $post->attachment_id = $img->id;
        }

        \Log::channel('errorlog')->debug("POSTをDB保存する前");

        // Auth::user()：現在認証されているユーザの取得
        //    ->posts()：リレーションシップ\Illuminate\Database\Eloquent\Relations\HasManyが返る
        //      ->save(<Model>)：Attach a model instance to the parent model.
        Auth::user()->posts()->save($post);
        \Log::channel('errorlog')->debug("POSTをDB保存した後");


        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($post, 201);
    }

    /**
     * 投稿一覧取得
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'image'])-> //リレーションシップuser＝投稿者情報も合わせて取得
            orderBy('CREATED_AT', 'desc')->paginate(5);
        // orderBy('CREATED_AT', 'desc')->get();

        return $posts;
    }


    /**
     * 指定されたIDの投稿の詳細（投稿と返信）を取得します
     * @return App\Post or 404エラー
     */
    public function detaile(string $id)
    {
        $post = Post::where('id', $id)->with(['user', 'image'])->first();
        $replies = Reply::where('parent_id', $id)->with(['user', 'image'])->paginate(3);

        // ??　はNULL合体演算子　前半の式が存在するときその式を、存在しないときは後半の式を返す
        return ['post' => $post, 'replies' => $replies] ?? abort(404);
    }

    /**
     * 投稿と紐づく返信を削除
     * @param \App\Http\Requests\DeletePostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function delete(\App\Http\Requests\DeletePostRequest $request, $id)
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


    /**
     * 指定のユーザの投稿を全て取得
     * @return App\User or 空文字
     */
    public function userPosts(Request $request, $id)
    {
        $posts = Post::where('user_id', $id)->with(['user'])->get();
        $replies = Reply::where('user_id', $id)->with(['user'])->get();

        //一つの配列にしてソートして返す
        $sortedData = $posts->concat($replies)->sortByDesc('updated_at');

        return $sortedData ?? abort(404);
    }
}
