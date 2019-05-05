<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\HeroPost::class, function (Faker $faker) {

    $heroPostType = \App\Domain\Models\HeroPostType::query()->inRandomOrder()->first();
    return [
        'hero_post_type_id' => $heroPostType->id,
        'squad_id' => function() {
            return factory(\App\Domain\Models\Squad::class)->create()->id;
        }
    ];
});
