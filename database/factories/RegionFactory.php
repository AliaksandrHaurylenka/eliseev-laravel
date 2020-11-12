<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Entity\Region::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->city,
        //'slug' => $faker->unique()->slug(2),
		'slug' => str_slug($name),
        'parent_id' => null,
    ];
});
