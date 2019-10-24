<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class AdminUserListGetApiTest extends TestCase
{
    use RefreshDatabase;

    //テスト前に実行したい前処理
    public function setUp(): void
    {
        parent::setUp();    //前処理をしたい場合必須

        // テストユーザー作成（デフォルトのものを使用）
        $this->users = factory(User::class, 5)->create();
    }

    /**
     *
     * @test
     */
    public function should_ユーザ一覧を取得できる()
    {

        // $response = $this->json('POST', route('login'), $loginInfo);
        // var_dump($response->content()); //JSONを取り出す

        // $response
        //     ->assertStatus(200)
        //     ->assertJson(['name' => $this->user->name]);

        // $this->assertAuthenticatedAs($this->user);

        $response = $this->json('GET', route('user.index'));

        $response->dump();

        $response
            ->assertStatus(200)
            ->assertJsonCount(5);

        $users = User::all();

        // data項目の期待値
        // $expected_data = $this->users->map(function ($user) {
        $expected_data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'provider_id' => $user->provider_id,
                'provider_name' => $user->provider_name,
                "created_at" =>  $user->created_at,
                "updated_at" =>  $user->updated_at,
                "name" => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ];
        })->all();

        // foreach ($response as $res) {
        //     $res->assertJsonFragment(['data' => $expected_data]);
        //     // $res->dump();
        // }

        $response->assertJsonFragment(['data' => $expected_data]);
    }
}
