<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

use App\User;
use Auth;

class OAuthController extends Controller
{
    /**
     * 各SNSのOAuth認証画面にリダイレクトして認証
     * @param string $provider サービス名
     * @return mixed
     */
    public function socialOAuth(string $provider)
    {
        \Log::channel('single')->debug("socialOAuth呼ばれました");
        if (Auth::check()) { //ログイン済みならindexへ
            return redirect('/');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * 各サイトからのコールバック
     * @param string $provider サービス名
     * @return mixed
     */
    public function handleProviderCallback(string $provider)
    {
        \Log::channel('single')->debug("handleProviderCallback呼ばれました");

        // $socialUser = Socialite::driver($provider)->stateless()->user();
        $socialUser = Socialite::driver($provider)->user();

        \Log::channel('single')->debug("socialUserを取得しました");

        $user = User::firstOrNew(['email' => $socialUser->getEmail()]);

        // \Log::channel('single')->debug($user);
        \Log::channel('single')->debug('名前取得：' . $socialUser->getName());
        \Log::channel('single')->debug('ニックネーム取得：' . $socialUser->getNickname());


        // すでに会員になっている場合の処理を書く
        // そのままログインさせてもいいかもしれない
        if ($user->exists) {
            Auth::login($user);
            return redirect('/');
        }


        $user->name = $socialUser->getName();
        $user->provider_id = $socialUser->getId();
        $user->provider_name = $provider;
        $user->save();

        Auth::login($user);

        return redirect('/');
        // return redirect()->route('home');
    }
}
