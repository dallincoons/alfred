<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'spotify_id' => str_random(10),
        'access_token' => str_random(),
        'refresh_token' => str_random(),
        'uri' => str_random(),
        'parent_id' => null,
        'remember_token' => str_random(10),
    ];
});
