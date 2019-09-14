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

//特定の投稿とそれに対する返信を取得
Route::get('/posts/{id}', 'PostController@detaile')->name('post.detaile');

//特定の投稿に対する返信を投稿
Route::post('/posts/{id}', 'PostController@reply')->name('post.reply');

//ユーザ登録 コントローラーは自前ではない
Route::post('/register', 'Auth\RegisterController@register')->name('register');

//ログイン　コントローラーは自前ではない
Route::post('/login', 'Auth\LoginController@login')->name('login');

//ログアウト　コントローラーは自前ではない
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//ログイン済みユーザの取得
Route::get('/user', 'PostController@user')->name('user');
