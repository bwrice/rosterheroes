<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Squad::class, function (Faker $faker) {

    return [
        'user_id' => factory(\App\Domain\Models\User::class)->create()->id,
        'uuid' => \Illuminate\Support\Str::uuid(),
        'province_id' => \App\Domain\Models\Province::getStarting()->id,
        'squad_rank_id' => \App\Domain\Models\SquadRank::getStarting()->id,
        'mobile_storage_rank_id' => \App\Domain\Models\MobileStorageRank::getStarting()->id,
        'spirit_essence' => \App\Domain\Models\Squad::STARTING_ESSENCE,
        'gold' => \App\Domain\Models\Squad::STARTING_GOLD,
        'favor' => \App\Domain\Models\Squad::STARTING_FAVOR,
        'name' => $faker->colorName . ' ' . $faker->word(),
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Squad::class, 'starting-posts', function (\App\Domain\Models\Squad $squad, $faker) {
    $squad->addStartingHeroPosts();
});
