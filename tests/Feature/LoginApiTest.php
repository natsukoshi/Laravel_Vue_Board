<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class LoginApiTest extends TestCase
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
    public function should_ユーザでログインできる()
    {

        $loginInfo = [
            'email' => $this->user->email,
            'password' => 'password'    //パスワードはpassword
        ];

        $response = $this->json('POST', route('login'), $loginInfo);
        var_dump($response->content()); //JSONを取り出す

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);

        $this->assertAuthenticatedAs($this->user);
    }
}
