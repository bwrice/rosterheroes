<?php

use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedMeasurableTypeStatTypeRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $relations = [
            [
                'measurable_type' => MeasurableType::PASSION,
                'stat_types' => [
                    StatType::PASS_TD,
                    StatType::RECEPTION,
                    StatType::INTERCEPTION,
                    StatType::STOLEN_BASE,
                    StatType::TRIPLE,
                    StatType::GOALIE_WIN,
                    StatType::HAT_TRICK,
                    StatType::THREE_POINTER,
                    StatType::BASKETBALL_BLOCK,
                ]
            ],
            [
                'measurable_type' => MeasurableType::BALANCE,
                'stat_types' => [
                    StatType::RUSH_TD,
                    StatType::PASS_YARD,
                    StatType::DOUBLE,
                    StatType::INNING_PITCHED,
                    StatType::EARNED_RUN_ALLOWED,
                    StatType::GOALIE_SAVE,
                    StatType::GOAL_AGAINST,
                    StatType::BASKETBALL_ASSIST,
                ]
            ],
            [
                'measurable_type' => MeasurableType::HONOR,
                'stat_types' => [
                    StatType::RUSH_YARD,
                    StatType::FUMBLE_LOST,
                    StatType::HIT,
                    StatType::STRIKEOUT,
                    StatType::BASE_ON_BALLS_AGAINST,
                    StatType::HIT_BATSMAN,
                    StatType::HOCKEY_ASSIST,
                    StatType::POINT_MADE,
                ]
            ],
            [
                'measurable_type' => MeasurableType::PRESTIGE,
                'stat_types' => [
                    StatType::REC_TD,
                    StatType::RUN_BATTED_IN,
                    StatType::BASE_ON_BALLS,
                    StatType::HIT_BY_PITCH,
                    StatType::COMPLETE_GAME,
                    StatType::COMPLETE_GAME_SHUTOUT,
                    StatType::GOAL,
                    StatType::HOCKEY_BLOCKED_SHOT,
                    StatType::REBOUND,
                ]
            ],
            [
                'measurable_type' => MeasurableType::WRATH,
                'stat_types' => [
                    StatType::REC_YARD,
                    StatType::HOME_RUN,
                    StatType::RUN_SCORED,
                    StatType::PITCHING_WIN,
                    StatType::PITCHING_SAVE,
                    StatType::HIT_AGAINST,
                    StatType::SHOT_ON_GOAL,
                    StatType::STEAL,
                    StatType::TURNOVER,
                ]
            ]
        ];


        $measurableTypes = MeasurableType::all();
        $statTypes = StatType::all();
        collect($relations)->each(function ($relationData) use($measurableTypes, $statTypes) {
            /** @var MeasurableType $matchingMeasurable */
            $matchingMeasurable = $measurableTypes->first(function (MeasurableType $measurableType) use ($relationData) {
                return $measurableType->name === $relationData['measurable_type'];
            });
            $statTypesToAttach = $statTypes->filter(function (StatType $statType) use ($relationData) {
                return in_array($statType->name, $relationData['stat_types']);
            });
            $matchingMeasurable->statTypes()->saveMany($statTypesToAttach);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
