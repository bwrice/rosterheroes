<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\ExternalTeam::class, function (Faker $faker, $args) {
    $integrationTypeID = $args['integration_type_id'];
    $leagueID = $args['league_id'] ?? null;
    return [
        'external_id' => uniqid(),
        'integration_type_id' => $integrationTypeID,
        'team_id' => function() use ($leagueID) {
            $args = [];
            if ($leagueID) {
                $args['league_id'] = $leagueID;
            }
            $team = factory(\App\Domain\Models\Team::class)->create($args);
            return $team->id;
        }
    ];
});
