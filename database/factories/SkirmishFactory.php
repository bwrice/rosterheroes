<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\Skirmish::class, function (Faker $faker) {
    return [
        'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
        'quest_id' => function() {
            return factory(\App\Domain\Models\Quest::class)->create()->id;
        }
    ];
});
