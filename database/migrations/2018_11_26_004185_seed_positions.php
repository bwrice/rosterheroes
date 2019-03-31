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
                    ['Quarterback', 'QB'],
                    ['Running Back', 'RB'],
                    ['Wide Receiver', 'WR'],
                    ['Tight End', 'TE']
                ],
            ],
            [
                'sport' => $baseball,
                'positions' => [
                    ['Catcher', 'C'],
                    ['First Base', '1B'],
                    ['Second Base', '2B'],
                    ['Third Base', '3B'],
                    ['Shortstop', 'SS'],
                    ['Pitcher', 'P'],
                    ['Outfield', 'OF']
                ],
            ],
            [
                'sport' => $basketball,
                'positions' => [
                    ['Point Guard', 'PG'],
                    ['Shooting Guard', 'SG'],
                    ['Small Forward', 'SF'],
                    ['Power Forward', 'PF'],
                    ['Center', 'C']
                ],
            ],
            [
                'sport' => $hockey,
                'positions' => [
                    ['Center', 'C'],
                    ['Left Wing', 'LW'],
                    ['Right Wing', 'RW'],
                    ['Defenseman', 'D'],
                    ['Goalie', 'G']
                ]
            ]
        ];

        foreach ($sports as $sport) {
            foreach ($sport['positions'] as $position) {
                \App\Domain\Models\Position::create([
                    'sport_id' => $sport['sport']->id,
                    'name' => $position[0],
                    'abbreviation' => $position[1]
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
