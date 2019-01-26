<?php

use Faker\Generator as Faker;

$factory->define(App\Skirmish::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'quest_id' => function() {
            return factory(\App\Campaigns\Quests\Quest::class)->create()->id;
        }
    ];
});
