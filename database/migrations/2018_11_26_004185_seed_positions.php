<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $football = \App\Domain\Models\Sport::where('name','Football')->first();
        $baseball = \App\Domain\Models\Sport::where('name', 'Baseball')->first();
        $basketball = \App\Domain\Models\Sport::where('name', 'Basketball')->first();
        $hockey = \App\Domain\Models\Sport::where('name', 'Hockey')->first();

        $sports = [
            [
                'sport' => $football,
                'positions' => [
                    \App\Domain\Models\Position::QUARTERBACK,
                    \App\Domain\Models\Position::RUNNING_BACK,
                    \App\Domain\Models\Position::WIDE_RECEIVER,
                    \App\Domain\Models\Position::TIGHT_END
//                    ['Quarterback', 'QB'],
//                    ['Running Back', 'RB'],
//                    ['Wide Receiver', 'WR'],
//                    ['Tight End', 'TE']
                ],
            ],
            [
                'sport' => $baseball,
                'positions' => [
                    \App\Domain\Models\Position::CATCHER,
                    \App\Domain\Models\Position::FIRST_BASE,
                    \App\Domain\Models\Position::SECOND_BASE,
                    \App\Domain\Models\Position::THIRD_BASE,
                    \App\Domain\Models\Position::SHORTSTOP,
                    \App\Domain\Models\Position::PITCHER,
                    \App\Domain\Models\Position::OUTFIELD
//                    ['Catcher', 'C'],
//                    ['First Base', '1B'],
//                    ['Second Base', '2B'],
//                    ['Third Base', '3B'],
//                    ['Shortstop', 'SS'],
//                    ['Pitcher', 'P'],
//                    ['Outfield', 'OF']
                ],
            ],
            [
                'sport' => $basketball,
                'positions' => [
                    \App\Domain\Models\Position::POINT_GUARD,
                    \App\Domain\Models\Position::SHOOTING_GUARD,
                    \App\Domain\Models\Position::SMALL_FORWARD,
                    \App\Domain\Models\Position::POWER_FORWARD,
                    \App\Domain\Models\Position::BASKETBALL_CENTER
//                    ['Point Guard', 'PG'],
//                    ['Shooting Guard', 'SG'],
//                    ['Small Forward', 'SF'],
//                    ['Power Forward', 'PF'],
//                    ['Center', 'C']
                ],
            ],
            [
                'sport' => $hockey,
                'positions' => [
                    \App\Domain\Models\Position::LEFT_WING,
                    \App\Domain\Models\Position::RIGHT_WING,
                    \App\Domain\Models\Position::DEFENSEMAN,
                    \App\Domain\Models\Position::GOALIE,
                    \App\Domain\Models\Position::HOCKEY_CENTER
//                    ['Center', 'C'],
//                    ['Left Wing', 'LW'],
//                    ['Right Wing', 'RW'],
//                    ['Defenseman', 'D'],
//                    ['Goalie', 'G']
                ]
            ]
        ];

        foreach ($sports as $sport) {
            foreach ($sport['positions'] as $positionName) {
                \App\Domain\Models\Position::create([
                    'sport_id' => $sport['sport']->id,
                    'name' => $positionName
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Domain\Models\Position::query()->delete();
    }
}
