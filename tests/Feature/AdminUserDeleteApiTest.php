<?php

namespace Tests\Feature;

use PHPUnit\Framework\Assert;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class AdminUserDeleteApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin_user = factory(User::class)->state('管理者')->create();
        $this->users = factory(User::class, 5)->create();
        $this->user = factory(User::class, 1)->create();
    }



    /**
     * @test
     */
    public function should_管理者によりユーザを削除()
    {

        //deleteAPIを叩く
        $response = $this->actingAs($this->admin_user)
            ->json(
                'DELETE',
                route('user.delete'),
                [
                    'id'   => $this->users[0]->id,
                ]
            );

        //レスポンスが204(No Content)であること
        $response->assertStatus(204);

        //削除済みのユーザのデータを取得
        $deletedUser = User::find($this->users[0]->id);
        var_dump(gettype($deletedUser->delete_flg));
        // dump($deletedUser);


        \PHPUnit\Framework\Assert::assertTrue($deletedUser->delete_flg);
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->name, "退会済み");
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->email, "");
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->password, "");

        //todo　削除したユーザでログインできないことを確認する
    }


    /**
     * @test
     */
    public function should_管理者ではないユーザで削除しようとしてエラー()
    {

        $testUser = $this->users[1];
        //deleteAPIを叩く
        $response = $this->actingAs($this->users[2])
            ->json(
                'DELETE',
                route('user.delete'),
                [
                    'id'   => $testUser->id,
                ]
            );

        //レスポンスが204(No Content)であること
        $response->assertStatus(403);

        //削除済みのユーザのデータを取得
        $deletedUser = User::find($testUser->id);
        // dump($deletedUser);

        \PHPUnit\Framework\Assert::assertFalse($deletedUser->delete_flg);
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->name, $testUser->name);
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->email, $testUser->email);
        \PHPUnit\Framework\Assert::assertEquals($deletedUser->password, $testUser->password);
    }
}
