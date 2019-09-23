<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use \PHPUnit\Framework\TestCase as PHPTestcase;

use App\User;
use App\Image;
use App\Post;
use App\Reply;

class PostDeleteApiTest extends TestCase
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
    public function should_投稿を削除（紐づく返信も画像も削除）()
    {
        //テストデータを1つ生成
        // factory(Post::class, 1)->create();
        $testMessage = 'test reply';
        $testTitle = "test reply title";

        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('post.create'),
                [
                    'message'   => $testMessage,
                    'title'   => $testTitle,
                    'img' => UploadedFile::fake()->image('image.jpg')
                ]
            );

        //ひとつ取得
        $post = Post::first();


        //返信をひとつ
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

        //返信をふたつ
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


        //紐づく返信を取得
        $postWithReplies = Post::where('id', $post->id)->with(['reply.image'])->orderBy('CREATED_AT', 'desc')->first();

        $image = Image::find($post->attachment_id);
        dump($image->file_name);


        //画像の消去確認用
        // $imagePath = "/home/vagrant/laravel/board/storage/app/public/img/" . $image->file_name;
        // PHPTestcase::assertFileExists($imagePath,  $imagePath . "が存在しません");
        Storage::disk('local')->assertExists("/public/img/" . $image->file_name);


        //　指定したユーザで認証して削除
        $response = $this->actingAs($this->user)
            ->json(
                'DELETE',
                route('posts.delete', ['id' => $post->id,])
            );

        // $response->dump();
        //レスポンスが204(No Content)であること
        $response->assertStatus(204);

        //削除済みを確認する
        $deletedPost = Post::find($post->id);
        PHPTestcase::assertNull($deletedPost);

        //画像が削除されているか確認
        // PHPTestcase::assertFileNotExists($imagePath,  $imagePath . "が存在します");
        Storage::disk('local')->assertMissing("/public/img/" . $image->file_name);


        //削除済みを確認する
        foreach ($postWithReplies->reply as $reply) {
            $deletedReply = Reply::find($reply->id);
            PHPTestcase::assertNull($deletedReply);
            //画像が削除されているか確認
            // PHPTestcase::assertFileNotExists($imagePath,  $imagePath . "が存在します");
            // dump($reply);
            Storage::disk('local')->assertMissing("/public/img/" . $reply->image->file_name);
        }
    }
}
