<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\User;

class PostController extends Controller
{

    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')
            ->except(['index']); //指定したメソッドだけ除外
    }

    /**
     * 投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $post = new Post;
        $post->message = $request->message;
        // $post->user_id = $request->user_id;

        // Auth::user()：現在認証されているユーザの取得
        //    ->posts()：リレーションシップ\Illuminate\Database\Eloquent\Relations\HasManyが返る
        //      ->save(<Model>)：Attach a model instance to the parent model.
        Auth::user()->posts()->save($post);

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($post, 201);
    }

    /**
     * 投稿一覧取得
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $posts = Post::with(['user'])-> //リレーションシップuser＝投稿者情報も合わせて取得
                orderBy('CREATED_AT', 'desc')->paginate();

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return $posts;
    }


    /**
     * ログイン済みのユーザを取得
     * @return App\User
     */
    public function user(){
        return Auth::user();
    }


}
