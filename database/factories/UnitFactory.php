<?php

use Faker\Generator as Faker;

$factory->define(App\Unit::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'rns_id' => $faker->numberBetween(1, 999),
        'number' => 'CC 117B',
        'name' => $faker->company,
        'location' => 'Beachfront',
        'type' => 'Vacation Rental'
    ];
});
