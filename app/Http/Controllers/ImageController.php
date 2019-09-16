<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Image;

class ImageController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        // $this->middleware('auth')
        //     ->except(['index', 'user', 'detaile']); //指定したメソッドだけ除外
    }
}
