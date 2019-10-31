<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Post;
use App\Reply;


class UserController extends Controller
{

    /**
     * ユーザ一覧取得
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\AdminUserRequest $request)
    {
        $users = User::orderBy('CREATED_AT', 'desc')->paginate(5);

        return $users;
    }


    /**
     * ログイン済みのユーザを取得
     * @return App\User or 空文字
     */
    public function user()
    {
        if (Auth::check()) {
            return Auth::user();
        } else {
            return '';
        }
    }

    /**
     * 指定のユーザを削除
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
