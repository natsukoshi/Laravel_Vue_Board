<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class LogoutApiTest extends TestCase
{
    use RefreshDatabase;

    //テスト前に実行したい前処理
    public function setUp():void{
        parent::setUp();    //前処理をしたい場合必須

         // テストユーザー作成（デフォルトのものを使用）
         $this->user = factory(User::class)->create();
    }

    /**
     *
     * @test
     */
    public function should_ログイン済みのユーザをログアウトさせる()
    {
        $response = $this->actingAs($this->user)    //ユーザを認証済み（ログイン）とする
                        ->json('POST', route('logout'));

        //ログアウト後のレスポンスが正常(200)であることを確認
        $response->assertStatus(200);

        //ユーザが認証されていないことを確認
        $this->assertGuest();
    }
}
