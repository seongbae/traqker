<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payout;
use Faker\Generator as Faker;

$factory->define(Payout::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale,
    ];
});
