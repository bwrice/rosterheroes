<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\CampaignStop::class, function (Faker $faker) {

    /** @var \App\Domain\Models\Quest $quest */
    $quest = factory(\App\Domain\Models\Quest::class)->create();

    /** @var \App\Domain\Models\Campaign $campaign */
    $campaign = factory(\App\Domain\Models\Campaign::class)->create([
        'continent_id' => $quest->province->continent->id
    ]);

    return [
        'uuid' => \Illuminate\Support\Str::uuid(),
        'campaign_id' => $campaign->id,
        'quest_id' => $quest->id,
        'province_id' => $quest->province->id
    ];
});
