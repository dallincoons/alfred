<?php

use App\Song;
use Faker\Generator as Faker;

$factory->define(Song::class, function (Faker $faker) {
    return [
        'external_id' => str_random(15),
        'title' => $faker->word,
        'artist_title' => $faker->firstName
    ];
});
