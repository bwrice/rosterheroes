<?php

use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(\App\Domain\Models\SideQuest::class, function (Faker $faker) {

    /** @var \App\Domain\Models\SideQuestBlueprint $blueprint */
    $blueprint = factory(\App\Domain\Models\SideQuestBlueprint::class)->create();
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'quest_id' => function() {
            return factory(\App\Domain\Models\Quest::class)->create()->id;
        },
        'name' => $blueprint->name,
        'side_quest_blueprint_id' => $blueprint->id
    ];
});
