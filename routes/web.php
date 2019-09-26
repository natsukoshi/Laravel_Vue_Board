<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::prefix('auth')->middleware('guest')->group(function () {

    // auth/{provider}
    Route::get('/{provider}', 'Auth\OAuthController@socialOAuth')
        ->where('provider', 'google')
        ->name('socialOAuth');

    // auth/{provider}/callback
    Route::get('/{provider}/callback', 'Auth\OAuthController@handleProviderCallback')
        ->where('provider', 'google')
        ->name('oauthCallback');
});

//どのURLへアクセスしてもindexへ飛ぶ
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');


Route::get('/home', function () {
    return view('welcome');
})->name('home');
