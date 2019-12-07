<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\ExternalGame::class, function (Faker $faker, $args) {
    $integrationTypeID = $args['integration_type_id'];
    $leagueID = $args['league_id'] ?? null;
    return [
        'external_id' => uniqid(),
        'integration_type_id' => $integrationTypeID,
        'game_id' => function() use ($leagueID, $integrationTypeID) {

            $leagueID = $leagueID ?: \App\Domain\Models\League::query()->inRandomOrder()->first()->id;

            /** @var \App\ExternalTeam $externalHomeTeam */
            $externalHomeTeam = factory(\App\ExternalTeam::class)->create([
                'integration_type_id' => $integrationTypeID,
                'team_id' => factory(\App\Domain\Models\Team::class)->create([
                    'league_id' => $leagueID
                ])
            ]);
            /** @var \App\ExternalTeam $externalAwayTeam */
            $externalAwayTeam = factory(\App\ExternalTeam::class)->create([
                'integration_type_id' => $integrationTypeID,
                'team_id' => factory(\App\Domain\Models\Team::class)->create([
                    'league_id' => $leagueID
                ])
            ]);

            /** @var \App\Domain\Models\Game $game */
            $game = factory(\App\Domain\Models\Game::class)->create([
                'home_team_id' => $externalHomeTeam->team->id,
                'away_team_id' => $externalAwayTeam->team->id,
            ]);

            return $game->id;
        }
    ];
});
