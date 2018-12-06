<?php

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
        $positions = \App\Position::with('sport')->get();
        $heroRaces = \App\HeroRace::all();

        $heroRacesArray = [
            [
                'name' => \App\HeroRace::ELF,
                'positions' => [
                    [
                        'name' => 'Quarterback',
                        'sport' => 'Football'
                    ],
                    [
                        'name' => 'Starting Pitcher',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Center',
                        'sport' => 'Basketball'
                    ],
                    [
                        'name' => 'Goalie',
                        'sport' => 'Hockey'
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::DWARF,
                'positions' => [
                    [
                        'name' => 'Running Back',
                        'sport' => 'Football'
                    ],
                    [
                        'name' => 'First Base',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Second Base',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Third Base',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Shortstop',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Small Forward',
                        'sport' => 'Basketball'
                    ],
                    [
                        'name' => 'Center',
                        'sport' => 'Hockey'
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::HUMAN,
                'positions' => [
                    [
                        'name' => 'Wide Receiver',
                        'sport' => 'Football'
                    ],
                    [
                        'name' => 'Outfield',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Point Guard',
                        'sport' => 'Basketball'
                    ],
                    [
                        'name' => 'Shooting Guard',
                        'sport' => 'Basketball'
                    ],
                    [
                        'name' => 'Left Wing',
                        'sport' => 'Hockey'
                    ],
                    [
                        'name' => 'Right Wing',
                        'sport' => 'Hockey'
                    ]
                ]
            ],
            [
                'name' => \App\HeroRace::ORC,
                'positions' => [
                    [
                        'name' => 'Tight End',
                        'sport' => 'Football'
                    ],
                    [
                        'name' => 'Catcher',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Relief Pitcher',
                        'sport' => 'Baseball'
                    ],
                    [
                        'name' => 'Power Forward',
                        'sport' => 'Basketball'
                    ],
                    [
                        'name' => 'Defenseman',
                        'sport' => 'Hockey'
                    ]
                ]
            ]
        ];

        foreach ($heroRacesArray as $heroRaceArray) {

            /** @var \App\HeroRace $heroRace */
            $heroRace = $heroRaces->where('name', '=', $heroRaceArray['name'])->first();

            foreach ($heroRaceArray['positions'] as $heroRacePosition) {

                $position = $positions->first(function(\App\Position $position) use($heroRacePosition) {
                    if ($position->name == $heroRacePosition['name']) {
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
