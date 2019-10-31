<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use App\Post;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPostsListApiTest extends TestCase
{
    use RefreshDatabase;


    //テスト前に実行したい前処理
    public function setUp(): void
    {
        parent::setUp();    //前処理をしたい場合必須

        //テスト対象ユーザ
        $this->testUser = factory(User::class)->create();

        //テスト対象のユーザの投稿を3件作り、それぞれに返信を1件ずつ作る
        $this->posts = factory(Post::class, 3)
            ->create(['user_id' => $this->testUser->id])
            ->each(function($post) {
                factory(Reply::class)
                ->create([
                    'parent_id' => $post->id,
                ]);
            });

        //別のユーザの投稿を1件作り、テスト対象のユーザで返信を1件作る
        $this->anotherPosts = factory(Post::class, 1)
            ->create()
            ->each(function($post) {
                factory(Reply::class)
                ->create([
                    'parent_id' => $post->id,
                    'user_id' => $this->testUser->id
                ]);
            });

    }


    /**
     * @test
     */
    public function should_特定のユーザの投稿一覧を取得できる()
    {
        //APIで取得
        $response = $this->actingAs($this->testUser)
            ->json(
                'GET',
                route('post.user',  [
                    'id'   => $this->testUser->id,
                ]),
            );

        $response
            ->assertStatus(200)
            ->assertJsonCount(4);

        //取得できた投稿のユーザIDがテストユーザのIDと一致することを確認
        $jsonData = $response->json();
        foreach($jsonData as $data){
            \PHPUnit\Framework\Assert::assertEquals( $this->testUser->id, $data['user_id']);
        }

    }

}
