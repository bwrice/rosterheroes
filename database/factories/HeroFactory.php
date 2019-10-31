<?php

use App\Domain\Models\HeroPost;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Domain\Models\Hero::class, function (Faker $faker) {

    $name = 'TestHero_' . random_int(1,999999999);
    $class = \App\Domain\Models\HeroClass::query()->inRandomOrder()->first();
    $heroRace = \App\Domain\Models\HeroRace::query()->inRandomOrder()->first();
    $rank = \App\Domain\Models\HeroRank::private();
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    return [
        'name' => $name,
        'uuid' => $uuid,
        'hero_class_id' => $class->id,
        'hero_rank_id' => $rank->id,
        'hero_race_id' => $heroRace->id,
        'combat_position_id' => function() {
            return \App\Domain\Models\CombatPosition::query()->inRandomOrder()->first()->id;
        }
    ];
});

$factory->afterCreatingState(\App\Domain\Models\Hero::class, 'with-measurables', function(\App\Domain\Models\Hero $hero, Faker $faker) {
    $measurableTypes = \App\Domain\Models\MeasurableType::heroTypes()->get();
    $measurableTypes->each(function (\App\Domain\Models\MeasurableType $measurableType) use ($hero) {
       $hero->measurables()->create([
           'uuid' => \Illuminate\Support\Str::uuid(),
           'measurable_type_id' => $measurableType->id,
           'amount_raised' => 0
       ]);
    });
});

$factory->afterCreatingState(\App\Domain\Models\Hero::class, 'with-squad', function(\App\Domain\Models\Hero $hero, Faker $faker) {
    $heroPostType = \App\Domain\Models\HeroPostType::query()->whereHas('heroRaces', function (\Illuminate\Database\Eloquent\Builder $builder) use ($hero) {
        return $builder->where('id', '=', $hero->hero_race_id);
    })->inRandomOrder()->first();

    $squad = factory(\App\Domain\Models\Squad::class)->create();

    factory(HeroPost::class)->create([
        'hero_id' => $hero->id,
        'squad_id' => $squad->id,
        'hero_post_type_id' => $heroPostType->id
    ]);
});
