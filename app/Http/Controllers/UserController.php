<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     // 認証が必要
    //     $this->middleware('auth')
    //         ->except(['user']); //指定したメソッドだけ除外
    // }


    /**
     * ユーザ一覧取得
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\AdminUserRequest $request)
    {
        \Log::channel('single')->debug("indexでusers取得後");

        $users = User::orderBy('CREATED_AT', 'desc')->paginate(5);

        \Log::channel('single')->debug("indexでusers取得後");

        return $users;
    }


    /**
     * ログイン済みのユーザを取得
     * @return App\User or 空文字
     */
    public function user()
    {
        if (Auth::check()) {
            // \Log::channel('single')->debug('ログインしてる');
            return Auth::user();
        } else {
            // \Log::channel('single')->debug('ログインしてない');
            return '';
        }
    }

    /**
     * ログイン済みのユーザを取得
     * @return App\User or 空文字
     */
    public function delete(\App\Http\Requests\AdminUserDeleteRequest $request)
    {
        $user = User::find($request->id);

        $user->delete_flg = true;
        $user->name = "退会済み";
        $user->email = "";
        $user->password = "";

        $user->update();

        return response("", 204);
    }
}
