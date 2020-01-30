<?php

use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(\App\Domain\Models\Skirmish::class, function (Faker $faker) {

    /** @var \App\Domain\Models\SkirmishBlueprint $blueprint */
    $blueprint = factory(\App\Domain\Models\SkirmishBlueprint::class)->create();
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'quest_id' => function() {
            return factory(\App\Domain\Models\Quest::class)->create()->id;
        },
        'name' => $blueprint->name,
        'skirmish_blueprint_id' => $blueprint->id
    ];
});
