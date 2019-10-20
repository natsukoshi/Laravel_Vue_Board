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

////////////////////////ユーザ登録・認証など////////////////////////////////////////
//ユーザ登録 コントローラーは自前ではない
Route::post('/register', 'Auth\RegisterController@register')->name('register');

//ログイン　コントローラーは自前ではない
Route::post('/login', 'Auth\LoginController@login')->name('login');

//ログアウト　コントローラーは自前ではない
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

//ログイン済みユーザの取得
Route::get('/user', 'PostController@user')->name('user');

////////////////////////ユーザ登録・認証など////////////////////////////////////////



////////////////////////PostController////////////////////////////////////////

// メッセージ投稿
Route::post('/posts', 'PostController@create')->name('post.create');

// 投稿一覧
Route::get('/posts', 'PostController@index')->name('post.index');

//特定の投稿とそれに対する返信を取得
Route::get('/posts/{id}', 'PostController@detaile')->name('post.detaile');

//指定したIDの返信投稿を削除する
Route::delete('/posts/{id}', 'PostController@delete')->name('posts.delete');

////////////////////////PostController////////////////////////////////////////


////////////////////////ReplyController////////////////////////////////////////

//特定の投稿idに対する返信を投稿
Route::post('/reply/{id}', 'ReplyController@create')->name('reply.create');

//指定したIDの返信投稿を削除する
Route::delete('/reply/{id}', 'ReplyController@delete')->name('reply.delete');

////////////////////////ReplyController////////////////////////////////////////


////////////////////////UserController////////////////////////////////////////

//ユーザの一覧を取得(管理用)
Route::get('/admin/users', 'UserController@index')->name('user.index');

//ユーザを削除(管理用)
Route::delete('/admin/users/{id}', 'UserController@delete')->name('user.delete');



////////////////////////UserController////////////////////////////////////////
