<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Post;
use App\Reply;
use App\User;

class PostDetaileTest extends TestCase
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
    public function should_返信がない投稿をAPIを使って取得()
    {
        //テストデータを1つ生成
        factory(Post::class, 1)->create();

        //ひとつ取得
        $post = Post::first();

        //API経由でIDを指定して投稿を取得
        $response = $this->json('GET', route('post.detaile', [
            'id' => $post->id
        ]));

        $response->dump();

        $response->assertJsonFragment(["data" => []]);
    }

    /**
     * @test
     */
    public function should_APIを使って投稿に返信＋その返信と投稿を取得()
    {
        //テストデータを1つ生成
        factory(Post::class, 1)->create();

        //ひとつ取得
        $post = Post::first();


        $testMessage = 'test message';
        $testTitle = "test title";
        $testImage = \Illuminate\Http\UploadedFile::fake()->image('image.png', 500, 500);
        // dump($testImage->extension());

        //　指定したユーザで認証してポスト
        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('reply.create', [
                    'id' => $post->id,
                ]),
                [
                    'parentID' => $post->id,
                    'message'   => $testMessage,
                    'title'   => $testTitle,
                    'img' => $testImage
                ]
            );

        $response->dump();
        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $reply = Reply::first();

        //投稿したメッセージとタイトルが一致すること
        $this->assertEquals($reply->message, $testMessage);
        $this->assertEquals($reply->title, $testTitle);

        //投稿者が一致すること
        $this->assertEquals($reply->user_id, $this->user->id);

        //    　指定したユーザで認証してポスト　2回め
        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('reply.create', [
                    'id' => $post->id,
                ]),
                [
                    'parentID' => $post->id,
                    'message'   => $testMessage,
                    'title'   => $testTitle,
                ]
            );

        //レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $reply = Reply::first();

        //投稿したメッセージとタイトルが一致すること
        $this->assertEquals($reply->message, $testMessage);
        $this->assertEquals($reply->title, $testTitle);

        //投稿者が一致すること
        $this->assertEquals($reply->user_id, $this->user->id);


        //API経由でIDを指定して投稿を取得
        $response = $this->json('GET', route('post.detaile', [
            'id' => $post->id
        ]));

        // \var_dump($reply);
        // \var_dump($response);

        // todo 取得できたデータの構造を確認する

        //取得できたデータを見やすい形式で出力する
        // $response->dump();

        $response->assertJsonStructure([
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
            "reply" => [
                [
                    "id",
                    "user_id",
                    "parent_id",
                    "message",
                    "title",
                    "created_at",
                    "updated_at",
                ],
                [
                    "id",
                    "user_id",
                    "parent_id",
                    "message",
                    "title",
                    "created_at",
                    "updated_at",
                ],
            ],
        ]);


        // $response->assertJsonFragment([
        //     "reply" =>
        //     [
        //         "parent_id" => $post->id,
        //         "message" => $testMessage,
        //         "title" => $testTitle,
        //     ]
        // ]);
    }



    /**
     * @test
     */
    public function should_APIを使って存在しない投稿に返信したときにエラーの確認()
    {
        //テストデータを1つ生成
        factory(Post::class, 1)->create();

        //ひとつ取得
        $post = Post::first();


        $testMessage = 'test message';
        $testTitle = "test title";

        //　指定したユーザで認証して存在しないポスト
        $response = $this->actingAs($this->user)
            ->json('POST', route('reply.create', [
                'id' => 100,
                'parent_id' => 100,
                'message'   => $testMessage,
                'title'   => $testTitle,
            ]));

        //レスポンスが201(CREATED)であること
        $response->assertStatus(422);

        $reply = Reply::first();
        $this->assertNull($reply);
    }
}
