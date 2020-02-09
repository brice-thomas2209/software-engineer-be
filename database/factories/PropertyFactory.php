<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Property;
use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

$factory->define(Property::class, function (Faker $faker) {
    // $faker->addProvider(new Faker\Provider\en_AU\Address($faker));
    return [
        'guid' => Uuid::uuid1(),
        'suburb' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country
    ];
});
