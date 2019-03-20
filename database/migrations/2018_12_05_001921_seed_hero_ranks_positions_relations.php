<?php

use App\Sport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedHeroRanksPositionsRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $positions = \App\Positions\Position::with('sport')->get();
        $heroRaces = \App\HeroRace::all();

        $heroRacesArray = [
            [
                'name' => \App\HeroRace::ELF,
                'positions' => [
                    [
                        'name' => 'Quarterback',
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => 'Third Base',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Shortstop',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Center',
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => 'Goalie',
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::DWARF,
                'positions' => [
                    [
                        'name' => 'Running Back',
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => 'First Base',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Second Base',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Small Forward',
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => 'Center',
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::HUMAN,
                'positions' => [
                    [
                        'name' => 'Wide Receiver',
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => 'Outfield',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Point Guard',
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => 'Shooting Guard',
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => 'Left Wing',
                        'sport' => Sport::HOCKEY
                    ],
                    [
                        'name' => 'Right Wing',
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::ORC,
                'positions' => [
                    [
                        'name' => 'Tight End',
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => 'Catcher',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Pitcher',
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => 'Power Forward',
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => 'Defenseman',
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ]
        ];

        foreach ($heroRacesArray as $heroRaceArray) {

            /** @var \App\HeroRace $heroRace */
            $heroRace = $heroRaces->where('name', '=', $heroRaceArray['name'])->first();

            foreach ($heroRaceArray['positions'] as $heroRacePosition) {

                $position = $positions->first(function(\App\Positions\Position $position) use($heroRacePosition) {
                    if ($position->name == $heroRacePosition['name']) {
                        // This conditional is needed because of multiple positions with the same name from different sports, ie Center
                        return $position->sport->name == $heroRacePosition['sport'];
                    }
                    return false;
                });

                $heroRace->positions()->attach($position->id);
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
        \Illuminate\Support\Facades\DB::table('hero_race_position')->truncate();
    }
}
