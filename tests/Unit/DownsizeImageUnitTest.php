<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\DB;

use App\Image;


class DownsizeImageUnitTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function should_規定サイズ以上の画像が縮小される_横長()
    {
        $testImage = \Illuminate\Http\UploadedFile::fake()->image('image.png', 1000, 500);

        $image = new Image;
        $resizedImage = $image->downsizeImage($testImage);

        $this->assertEquals($resizedImage->width(), 500);
        $this->assertEquals($resizedImage->height(), 250);
    }

    /**
     * @test
     */
    public function should_規定サイズ以上の画像が縮小される_縦長()
    {
        $testImage = \Illuminate\Http\UploadedFile::fake()->image('image.png', 500, 1000);

        $image = new Image;
        $resizedImage = $image->downsizeImage($testImage);

        $this->assertEquals($resizedImage->width(), 250);
        $this->assertEquals($resizedImage->height(), 500);
    }

    /**
     * @test
     */
    public function should_規定サイズ以下の画像は縮小さない()
    {
        $testImage = \Illuminate\Http\UploadedFile::fake()->image('image.png', 499, 499);

        $image = new Image;
        $resizedImage = $image->downsizeImage($testImage);

        $this->assertEquals($resizedImage->width(), 499);
        $this->assertEquals($resizedImage->height(), 499);
    }

    /**
     * @test
     */
    public function should_縮小された画像が正しく保存できる()
    {
        $testImage = \Illuminate\Http\UploadedFile::fake()->image('image.png', 500, 1000);

        $image = new Image;
        $image->saveImage($testImage);

        Storage::disk('local')->assertExists("/public/img/" . $image->file_name);
    }


    /**
     *
     */
    public function should_envメソッド動いてるか確認()
    {
        // $db = env("connections.sqlite_testsing");
        $db = env('APP_ENV');
        dump($db);

        $config = config("database.connections");
        // dump($config);

        DB::connection()->getPdo();
        echo (DB::connection()->getDatabaseName());
    }
}
