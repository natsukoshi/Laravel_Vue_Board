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
}
