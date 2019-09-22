<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \PHPUnit\Framework\TestCase as PHPTestcase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
    public function should_返信を削除（画像も削除）()
    {
        //テストデータを1つ生成
        factory(Post::class, 1)->create();

        //ひとつ取得
        $post = Post::first();

        $testMessage = 'test reply';
        $testTitle = "test reply title";

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('post.reply', ['id' => $post->id]),
                [
                    'parentID' => $post->id,
                    'message'   => $testMessage,
                    'title'   => $testTitle,
                    'img' => UploadedFile::fake()->image('image.jpg')
                ]
            );

        // $response->dump();
        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $reply = Reply::first();
        dump($reply);

        $image = Image::find($reply->attachment_id);
        if ($image != null) {
            dump($image);
        }

        //画像の消去確認用
        // $imagePath = "/home/vagrant/laravel/board/storage/app/public/img/" . $image->file_name;
        // PHPTestcase::assertFileExists($imagePath,  $imagePath . "が存在しません");
        Storage::disk('local')->assertExists("/public/img/" . $image->file_name);


        //　指定したユーザで認証して削除
        $response = $this->actingAs($this->user)
            ->json(
                'DELETE',
                route('reply.delete', ['id' => $reply->id,])
            );

        $response->dump();
        //レスポンスが204(No Content)であること
        $response->assertStatus(204);

        //削除済みを確認する
        $deletedReply = Reply::find($reply->id);
        dump($deletedReply);

        PHPTestcase::assertNull($deletedReply);

        //画像が削除されているか確認
        // PHPTestcase::assertFileNotExists($imagePath,  $imagePath . "が存在します");
        Storage::disk('local')->assertMissing("/public/img/" . $image->file_name);
    }
}
