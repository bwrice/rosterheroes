<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Models\ExternalGame::class, function (Faker $faker, $args) {
    $integrationTypeID = $args['integration_type_id'];
    return [
        'external_id' => uniqid(),
        'integration_type_id' => $integrationTypeID,
        'game_id' => function() use ($integrationTypeID) {

            $leagueID = \App\Domain\Models\League::query()->inRandomOrder()->first()->id;

            /** @var \App\Domain\Models\ExternalTeam $externalHomeTeam */
            $externalHomeTeam = factory(\App\Domain\Models\ExternalTeam::class)->create([
                'integration_type_id' => $integrationTypeID,
                'team_id' => factory(\App\Domain\Models\Team::class)->create([
                    'league_id' => $leagueID
                ])
            ]);
            /** @var \App\Domain\Models\ExternalTeam $externalAwayTeam */
            $externalAwayTeam = factory(\App\Domain\Models\ExternalTeam::class)->create([
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
