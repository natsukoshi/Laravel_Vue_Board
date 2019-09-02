<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Post;

class PostSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function should_APIを使って投稿できる()
    {
        $testMessage = 'test message';
        $response = $this
            ->json('POST', route('post.create'),
            [
                'message' => $testMessage,
            ]
        );

        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $post = Post::first();
        var_dump($post);

        //投稿したメッセージが一致すること
        $this->assertEquals($post->message, $testMessage);
    }


}
