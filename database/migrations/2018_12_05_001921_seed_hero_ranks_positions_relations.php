<?php

use App\Domain\Models\Sport;
use App\Domain\Models\Position;
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
        $positions = \App\Domain\Models\Position::with('sport')->get();
        $heroRaces = \App\Domain\Models\HeroRace::all();

        $heroRacesArray = [
            [
                'name' => \App\Domain\Models\HeroRace::ELF,
                'positions' => [
                    [
                        'name' => Position::QUARTERBACK,
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => Position::THIRD_BASE,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::SHORTSTOP,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::BASKETBALL_CENTER,
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => Position::GOALIE,
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\Domain\Models\HeroRace::DWARF,
                'positions' => [
                    [
                        'name' => Position::RUNNING_BACK,
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => Position::FIRST_BASE,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::SECOND_BASE,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::SMALL_FORWARD,
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => Position::HOCKEY_CENTER,
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\Domain\Models\HeroRace::HUMAN,
                'positions' => [
                    [
                        'name' => Position::WIDE_RECEIVER,
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => Position::OUTFIELD,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::POINT_GUARD,
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => Position::SHOOTING_GUARD,
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => Position::LEFT_WING,
                        'sport' => Sport::HOCKEY
                    ],
                    [
                        'name' => Position::RIGHT_WING,
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ],
            [
                'name' => \App\Domain\Models\HeroRace::ORC,
                'positions' => [
                    [
                        'name' => Position::TIGHT_END,
                        'sport' => Sport::FOOTBALL
                    ],
                    [
                        'name' => Position::CATCHER,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::PITCHER,
                        'sport' => Sport::BASEBALL
                    ],
                    [
                        'name' => Position::POWER_FORWARD,
                        'sport' => Sport::BASKETBALL
                    ],
                    [
                        'name' => Position::DEFENSEMAN,
                        'sport' => Sport::HOCKEY
                    ]
                ]
            ]
        ];

        foreach ($heroRacesArray as $heroRaceArray) {

            /** @var \App\Domain\Models\HeroRace $heroRace */
            $heroRace = $heroRaces->where('name', '=', $heroRaceArray['name'])->first();

            foreach ($heroRaceArray['positions'] as $heroRacePosition) {

                $position = $positions->first(function(\App\Domain\Models\Position $position) use($heroRacePosition) {
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
