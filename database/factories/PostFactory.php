<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\User;
use Faker\Generator as Faker;

//　テスト用の投稿を自動生成
$factory->define(App\Post::class, function (Faker $faker) {
    return [
        // idは自動付与？
        'message' => $faker->asciify('********************'), //ランダム文字列　*を適当な文字で置き換える
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'attachment_id' => function () {
            return factory(App\Image::class)->create()->id;
        },
        'title' => $faker->asciify('Dammy Title *****'),
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
