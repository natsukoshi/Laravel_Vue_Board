<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker, string $filename) {
    return [
        'file_name' => $filename,
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
