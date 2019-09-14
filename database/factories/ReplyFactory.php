<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Post;
use App\User;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'message' => $faker->asciify('********************'), //ランダム文字列　*を適当な文字で置き換える
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'parent_post_id' => function () {
            return factory(App\Post::class)->create()->id;
        },
        'title' => $faker->asciify('Dammy Title *****'),
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
