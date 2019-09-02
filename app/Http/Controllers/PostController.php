<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    /**
     * テキスト投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $post = new Post;
        $post->message = $request->message;

        $post->save();

        // リソースの新規作成なのでレスポンスコードはCREATED(201)を返却
        return response($post, 201);
    }
}
