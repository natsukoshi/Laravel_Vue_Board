<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    /**
     * 投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $post = new Post;
        $post->message = $request->message;

        $post->save();
        \Log::channel('single')->debug('投稿');


        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($post, 201);
    }

    /**
     * 投稿一覧取得
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(){
        // $posts = Post::with(['owner'])
        // ->orderBy(Photo::CREATED_AT, 'desc')->paginate();
        $posts = Post::orderBy('CREATED_AT', 'desc')->paginate();

        \Log::channel('single')->debug('投稿一覧');

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return $posts;
    }


}
