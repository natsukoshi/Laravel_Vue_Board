<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Post;

class PostListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function should_APIを使って投稿一覧を取得できる()
    {
        //テストデータを５つ生成
        factory(Post::class, 5)->create();

        //API経由でデータを取得
        $response = $this->json('GET', route('post.index') );

        //DBから直接データを取得
        $posts = Post::with(['user'])->orderBy('CREATED_AT', 'desc')->get();

        // foreach($posts as $post){
        //     var_dump($post->id);
        //     var_dump($post->message);
        // }
         // data項目の期待値
         $expected_data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'message' => $post->message,
                'user' => [
                    'name' => $post->user->name,
                ],
            ];
        })
        ->all();

        foreach($posts as $post){
            var_dump('ID:' . $post->id . ' NAME:' . $post->user->name);
        }

        $response->assertStatus(200)
            // レスポンスJSONのdata項目に含まれる要素が5つであること
            ->assertJsonCount(5, 'data');

    }
}
