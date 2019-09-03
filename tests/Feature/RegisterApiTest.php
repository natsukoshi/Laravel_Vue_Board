<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     *
     * @test
     */
    public function should_ユーザをテーブルに保存できる()
    {
        // ここで処理を止め、テストが未完成であるという印をつけます。
        // $this->markTestIncomplete(
        //     'このテストは、まだ実装されていません。'
        // );

        $data = [
            'name' => 'test user',
            'email' => 'dummy@email.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }
}
