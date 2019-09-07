<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class LoggedinUserApiTest extends TestCase
{

    use RefreshDatabase;

    //テスト前に実行したい前処理
    public function setUp():void{
        parent::setUp();    //前処理をしたい場合必須

         // テストユーザー作成（デフォルトのものを使用）
         $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_ログイン済みのユーザ情報を取得できる()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user'));

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);
    }

    /**
     * @test
     */
    public function should_ログインされていない場合は空文字を返却する()
    {
        // ユーザーが認証されていないことを確認
        $this->assertGuest();

        $response = $this->json('GET', route('user'));

        // $response->assertStatus(200);
        $this->assertEquals("", $response->content());
    }
}
