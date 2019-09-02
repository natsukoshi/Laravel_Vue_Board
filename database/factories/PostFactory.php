<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

//　テスト用の投稿を自動生成
$factory->define(App\Post::class, function (Faker $faker) {
    return [
        // idは自動付与？
        'message' => $faker->asciify('********************'), //ランダム文字列　*を適当な文字で置き換える
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
