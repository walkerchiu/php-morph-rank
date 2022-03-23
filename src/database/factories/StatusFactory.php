<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\MorphRank\Models\Entities\Status;
use WalkerChiu\MorphRank\Models\Entities\StatusLang;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'morph_type' => config('wk-core.class.group.group'),
        'morph_id'   => 1,
        'order'      => $faker->randomNumber
    ];
});

$factory->define(StatusLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
