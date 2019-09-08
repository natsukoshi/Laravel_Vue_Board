<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;  //追加
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ログイン成功後のレスポンス
    // AuthenticatesUsersトレイトのメソッドの上書き
    protected function authenticated(Request $request, $user)
    {
        // \Log::channel('single')->debug("返り値user：" . $user);
        return $user;
    }

    // ログアウト後のレスポンス ステータスコード200だけを返す
    // AuthenticatesUsersトレイトのメソッドの上書き
    protected function loggedOut(Request $request)
    {
        // セッションを再生成する→logoutメソッドで同じことをしているため不要
        // $request->session()->regenerate();

        // データとしては空で、ステータスコード200だけを返す
        // (jsonメソッドのデフォルト値が200になっている）
        return response()->json();
    }
}
