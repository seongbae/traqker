<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Hour;
use Faker\Generator as Faker;

$factory->define(Hour::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale,
    ];
});
