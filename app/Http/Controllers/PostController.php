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
            ->except(['index', 'user', 'detaile']); //指定したメソッドだけ除外
    }

    /**
     * 投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){

        $validateRule = [
            'message' => 'required',
            'title' => 'required | max:255',
        ];

        $this->validate($request, $validateRule);

        $post = new Post;
        $post->message = $request->message;
        $post->title = $request->title;
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
     * @return App\User or 空文字
     */
    public function user(){
        if(Auth::check()){
            // \Log::channel('single')->debug('ログインしてる');
            return Auth::user();
        }else{
            // \Log::channel('single')->debug('ログインしてない');
            return '';
        }
    }

    /**
     * 指定されたIDの投稿の詳細（投稿と返信）を取得します
     * @return App\Post or 404エラー
     */
    public function detaile(string $id){
        $post = Post::where('id', $id)->with(['user'])->first();

        // ??　はNULL合体演算子　前半の式が存在するときその式を、存在しないときは後半の式を返す
        return $post ?? abort(404);
    }





}
