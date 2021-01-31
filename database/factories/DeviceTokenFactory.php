<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DeviceToken;
use Faker\Generator as Faker;

$factory->define(DeviceToken::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale,
    ];
});
