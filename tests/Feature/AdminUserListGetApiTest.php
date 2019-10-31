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
        $this->admin_user = factory(User::class)->state('管理者')->create();
        $this->users = factory(User::class, 4)->create();
    }

    /**
     *
     * @test
     */
    public function should_ユーザ一覧を取得できる()
    {

        $response = $this->actingAs($this->admin_user)
                        ->json('GET', route('user.index'));

        // $response->dump();

        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');

        $users = User::all();

        // data項目の期待値
        $expected_data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'provider_id' => $user->provider_id,
                'provider_name' => $user->provider_name,
                "created_at" =>  $user->created_at->format('Y-m-d H:i:s'),
                "updated_at" =>  $user->updated_at->format('Y-m-d H:i:s'),
                "name" => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at->format('Y-m-d H:i:s'),
            ];
        })->all();

        foreach($expected_data as $data){
            $response->assertJsonFragment($data);
        }
    }
}
