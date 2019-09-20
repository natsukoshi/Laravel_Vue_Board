<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \PHPUnit\Framework\TestCase as PHPTestcase;

use App\User;
use App\Image;
use App\Post;
use App\Reply;

class ReplyDeleteApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_返信を削除（一旦画像抜き）()
    {
        //テストデータを1つ生成
        factory(Post::class, 1)->create();

        //ひとつ取得
        $post = Post::first();

        $testMessage = 'test message';
        $testTitle = "test title";

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.reply', [
                'id' => $post->id,
                'parentID' => $post->id,
                'message'   => $testMessage,
                'title'   => $testTitle,
            ]));

        // $response->dump();
        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $reply = Reply::first();
        // dump($reply);

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json('DELETE', route('reply.delete', [
                'id' => $reply->id,
            ]));

        // $response->dump();
        //レスポンスが204(No Content)であること
        $response->assertStatus(204);

        //削除済みを確認する
        $deletedReply = Reply::find($reply->id);
        dump($deletedReply);

        PHPTestcase::assertNull($deletedReply);

        //todo 画像が削除されているか確認

    }
}
