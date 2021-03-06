<?php

use App\Song;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Room::class, function (Faker $faker) {
    return [
        'user_id' => \Auth::user()->getKey(),
        'name' => $faker->word,
        'playlistId' => str_random(10),
        'deviceId' => str_random(10),
        'code' => str_random(10)
    ];
});
