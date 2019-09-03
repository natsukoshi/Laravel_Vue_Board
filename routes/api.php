<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



// メッセージ投稿
Route::post('/posts', 'PostController@create')->name('post.create');

// 投稿一覧
Route::get('/posts', 'PostController@index')->name('post.index');

//ユーザ登録 コントローラーは自前ではない
Route::post('/register', 'Auth\RegisterController@register')->name('register');

//ログイン　コントローラーは自前ではない
Route::post('/login', 'Auth\LoginController@login')->name('login');

//ログアウト　コントローラーは自前ではない
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
