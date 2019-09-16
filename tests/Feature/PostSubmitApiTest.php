<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Post;
use App\User;
use Illuminate\Http\UploadedFile;

class PostSubmitApiTest extends TestCase
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
    public function should_APIを使って投稿できる（ユーザ認証有り）()
    {
        $testMessage = 'test message';
        $testTitle = "test title";
        $testImageFileName = 'image.jpg';

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('post.create'),
                [
                    'message' => $testMessage,
                    'title' => $testTitle,
                    'img' => UploadedFile::fake()->image('image.jpg')
                ]
            );

        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $post = Post::with(['image'])->first();

        //投稿したメッセージとタイトルが一致すること
        $this->assertEquals($post->message, $testMessage);
        $this->assertEquals($post->title, $testTitle);

        //投稿者が一致すること
        $this->assertEquals($post->user_id, $this->user->id);

        //保存されたファイル名のファイルが存在すること
        $this->assertFileExists(public_path() . "/img/" . $post->image->file_name);

        var_dump($post->user->name);
    }
}
