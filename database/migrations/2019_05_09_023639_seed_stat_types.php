<?php

use Illuminate\Database\Migrations\Migration;

class SeedStatTypes extends Migration
{

    public function up()
    {
        $groups = [
            [
                'sport' => \App\Domain\Models\Sport::FOOTBALL,
                'stat_types' => [
                    \App\Domain\Models\StatType::PASS_TD,
                    \App\Domain\Models\StatType::RUSH_TD,
                    \App\Domain\Models\StatType::REC_TD,
                    \App\Domain\Models\StatType::PASS_YARD,
                    \App\Domain\Models\StatType::RUSH_YARD,
                    \App\Domain\Models\StatType::REC_YARD,
                    \App\Domain\Models\StatType::RECEPTION,
                    \App\Domain\Models\StatType::INT,
                    \App\Domain\Models\StatType::FUMBLE
                ]
            ],
            [
                'sport' => \App\Domain\Models\Sport::BASEBALL,
                'stat_types' => [

                    \App\Domain\Models\StatType::SINGLE,
                    \App\Domain\Models\StatType::DOUBLE,
                    \App\Domain\Models\StatType::TRIPLE,
                    \App\Domain\Models\StatType::HR,
                    \App\Domain\Models\StatType::BB,
                    \App\Domain\Models\StatType::HBP,
                    \App\Domain\Models\StatType::SB,
                    \App\Domain\Models\StatType::INNING_PITCHED,
                    \App\Domain\Models\StatType::K,
                    \App\Domain\Models\StatType::PITCHING_WIN,
                    \App\Domain\Models\StatType::EARNED_RUN_ALLOWED,
                    \App\Domain\Models\StatType::HIT_AGAINST,
                    \App\Domain\Models\StatType::BASE_ON_BALLS_AGAINST,
                    \App\Domain\Models\StatType::HIT_BATSMAN,
                    \App\Domain\Models\StatType::COMPLETE_GAME,
                    \App\Domain\Models\StatType::COMPLETE_GAME_SHUTOUT,
                    \App\Domain\Models\StatType::NO_HITTER
                ]
            ],
            [
                'sport' => \App\Domain\Models\Sport::HOCKEY,
                'stat_types' => [
                    \App\Domain\Models\StatType::GOAL,
                    \App\Domain\Models\StatType::HOCKEY_ASSIST,
                    \App\Domain\Models\StatType::SHOT_ON_GOAL,
                    \App\Domain\Models\StatType::BLOCKED_SHOT,
                    \App\Domain\Models\StatType::GOALIE_WIN,
                    \App\Domain\Models\StatType::SAVE,
                    \App\Domain\Models\StatType::GOAL_AGAINST,
                    \App\Domain\Models\StatType::HAT_TRICK
                ]
            ],
            [
                'sport' => \App\Domain\Models\Sport::BASKETBALL,
                'stat_types' => [
                    \App\Domain\Models\StatType::POINT,
                    \App\Domain\Models\StatType::THREE_POINTER,
                    \App\Domain\Models\StatType::REBOUND,
                    \App\Domain\Models\StatType::BASKETBALL_ASSIST,
                    \App\Domain\Models\StatType::STEAL,
                    \App\Domain\Models\StatType::BLOCK,
                    \App\Domain\Models\StatType::TURNOVER
                ]
            ],
        ];

        foreach ($groups as $group) {
            $sport = \App\Domain\Models\Sport::where('name', '=', $group['sport'])->first();
            foreach ($group['stat_types'] as $stat_type) {
                \App\Domain\Models\StatType::create([
                    'sport_id' => $sport->id,
                    'name' => $stat_type
                ]);
            }
        }
    }

    public function down()
    {
        //
    }
}