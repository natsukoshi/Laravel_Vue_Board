<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Mockery;
use App\User;
use Auth;
use Socialite;


class OAuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        $this->providerName = 'google';

        // モックを作成
        $this->user = Mockery::mock('Laravel\Socialite\Two\User');
        $this->user
            ->shouldReceive('getId')
            ->andReturn(uniqid())
            ->shouldReceive('getEmail')
            ->andReturn(uniqid() . '@test.com')
            ->shouldReceive('getNickname')
            ->andReturn('Pseudo');

        $this->provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $this->provider->shouldReceive('user')->andReturn($this->user);
    }

    public static function tearDownAfterClass(): void
    {
        // Mockeryの設定をもとに戻す
        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    /**
     * @test
     */
    public function Googleの認証画面を表示できる()
    {
        // URLをコール
        $responce = $this->get(route('socialOAuth', ['provider' => $this->providerName]));

        // 200 -> 302 に変更
        $responce->assertStatus(302);
        $this->assertThat(
            // リダイレクト先のURLのドメインが正しいかを検証
            $responce->getTargetUrl(),
            $this->stringStartsWith('https://accounts.google.com/')
        );
    }

    /**
     * @test
     */
    public function Googleアカウントでユーザー登録できる()
    {
        Socialite::shouldReceive('driver')->with($this->providerName)->andReturn($this->provider);

        // URLをコール
        $this->get(route('oauthCallback', ['service' => $this->providerName]))
            ->assertStatus(302)
            ->assertRedirect(route('home'));

        // 登録されているユーザーデータを取得
        $user = User::where(['email' => $this->user->getEmail()])->firstOrFail();
        // 各データが正しく登録されているかチェック
        $this->assertEquals($user->provider_id, $this->user->getId());
        $this->assertEquals($user->provider_name, $this->providerName);
        $this->assertEquals($user->name, $this->user->getNickName());
        $this->assertEquals($user->email, $this->user->getEmail());

        // 認証チェック
        $this->assertTrue(Auth::check());
    }
}
