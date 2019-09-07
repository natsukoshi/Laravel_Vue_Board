<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Post;
use App\User;


class PostSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function should_APIを使って投稿できる（ユーザ認証有り）()
    {
        $testMessage = 'test message';

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.create'),
            [
                'message' => $testMessage,
            ]
        );

        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $post = Post::first();

        //投稿したメッセージが一致すること
        $this->assertEquals($post->message, $testMessage);

        //投稿者が一致すること
        $this->assertEquals($post->user_id, $this->user->id);

        var_dump($post->user->name);
    }

}
