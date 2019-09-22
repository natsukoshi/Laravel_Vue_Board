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
        factory(Post::class, 3)->create();

        //API経由でデータを取得
        $response = $this->json('GET', route('post.index'));

        //DBから直接データを取得
        $posts = Post::with(['user', 'image'])->orderBy('CREATED_AT', 'desc')->get();
        dump($posts);

        // foreach($posts as $post){
        //     var_dump($post->id);
        //     var_dump($post->message);
        // }
        // data項目の期待値
        $expected_data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'message' => $post->message,
                'title' => $post->title,
                "created_at" =>  $post->created_at,
                "updated_at" =>  $post->updated_at,
                "image" => $post->image,
                'user_id' => $post->user_id,
                'user' => $post->user,
                'attachment_id' => $post->attachment_id,

                // 'user' => [
                //     'name' => $post->user->name,
                // ],
                // 'image' => [
                //     'id' => $post->attchment_id,
                // ]
            ];
        })->all();

        $expected_structure = [
            "id",
            "user_id",
            "message",
            "title",
            "updated_at",
            "created_at",
            "user" => [
                "id",
                "name",
                "email",
                "email_verified_at",
                "created_at",
                "updated_at",
            ],
            "image" => [
                "id",
                "file_name",
                "file_url",
                "created_at",
                "updated_at",
            ]
        ];
        // dump($expected_data);

        // foreach($posts as $post){
        //     var_dump('ID:' . $post->id . ' NAME:' . $post->user->name);
        // }

        $response->assertStatus(200)
            // レスポンスJSONのdata項目に含まれる要素が5つであること
            ->assertJsonCount(3, 'data')
            // ->assertJsonFragment([
            //     "data" => $expected_data,
            // ]);
            // ->assertJsonStructure(
            //     $expected_structure,
            // )
            ->assertJsonFragment(
                ["data" => $expected_data,]
                // $expected_data,
            );
    }
}
