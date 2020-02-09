<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AnalyticType;
use Faker\Generator as Faker;

$factory->define(AnalyticType::class, function (Faker $faker) {
    return [
        'name' => collect(['max_Bld_Height_m', 'min_lot_size_m2', 'fsr'])->random(),
        'units' => collect(['m', 'm2', ':1'])->random(),
        'is_numeric' => $faker->boolean,
        'num_decimal_places' => $faker->randomDigit
    ];
});
