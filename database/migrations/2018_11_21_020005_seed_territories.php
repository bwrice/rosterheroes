<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTerritories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $continents = \App\Continent::all();

        $territories = [
        [
            'name' => \App\Territory::GARDENS_OF_REDEMPTION,
            'continents' => [
                \App\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Territory::WOODS_OF_THE_WILD,
            'continents' => [
                \App\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Territory::TWISTING_ISLES_OF_ILLUSIONS,
            'continents' => [
                \App\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Territory::GRASSLANDS_OF_GIANTS,
            'continents' => [
                \App\Continent::FETROYA,
                \App\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::MENACING_MARCHES,
            'continents' => [
                \App\Continent::FETROYA,
                \App\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::TROPICS_OF_TREPIDATION,
            'continents' => [
                \App\Continent::FETROYA,
                \App\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::PERILOUS_PLANS,
            'continents' => [
                \App\Continent::EAST_WOZUL,
                \App\Continent::WEST_WOZUL,
                \App\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::TREACHEROUS_FOREST,
            'continents' => [
                \App\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::SAVAGE_SWAMPS,
            'continents' => [
                \App\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::DESERT_OF_DESPAIR,
            'continents' => [
                \App\Continent::EAST_WOZUL,
                \App\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::HUMBLING_HILLS,
            'continents' => [
                \App\Continent::NORTH_JAGONETH,
                \App\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::GULF_OF_SERPENTS,
            'continents' => [
                \App\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::CANNIBAL_CANYONS,
            'continents' => [
                \App\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Territory::INFERNAL_ISLANDS,
            'continents' => [
                \App\Continent::WEST_WOZUL,
                \App\Continent::CENTRAL_JAGONETH,
                \App\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::JADE_JUNGLE,
            'continents' => [
                \App\Continent::NORTH_JAGONETH,
                \App\Continent::CENTRAL_JAGONETH,
                \App\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::SHORES_OF_THE_SHADOWS,
            'continents' => [
                \App\Continent::NORTH_JAGONETH,
                \App\Continent::CENTRAL_JAGONETH,
                \App\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Territory::VALLEY_OF_VANISHINGS,
            'continents' => [
                \App\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::PRIMAL_PASSAGE,
            'continents' => [
                \App\Continent::WEST_WOZUL,
                \App\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::ARCTIC_ARCHIPELAGO,
            'continents' => [
                \App\Continent::NORTH_JAGONETH,
                \App\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Territory::BADLANDS_OF_THE_LEVIATHAN,
            'continents' => [
                \App\Continent::CENTRAL_JAGONETH,
                \App\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::PEAKS_OF_PANDORA,
            'continents' => [
                \App\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::CRYPTIC_COAST,
            'continents' => [
                \App\Continent::CENTRAL_JAGONETH,
                \App\Continent::SOUTH_JAGONETH,
                \App\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Territory::WETLANDS_OF_WANDERING_TORMENT,
            'continents' => [
                \App\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Territory::BLOODSTONE_BEACHES,
            'continents' => [
                \App\Continent::SOUTH_JAGONETH,
                \App\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Territory::TURBULENT_TUNDRA,
            'continents' => [
                \App\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Territory::COVES_OF_CORRUPTION,
            'continents' => [
                \App\Continent::SOUTH_JAGONETH,
                \App\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Territory::MOUNTAINS_OF_MISERY,
            'continents' => [
                \App\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Territory::ENCHANTED_ENCLAVE_OF_VISIONS,
            'continents' => [
                \App\Continent::SOUTH_JAGONETH,
                \App\Continent::VINDOBERON,
                \App\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Territory::SCREAMING_HIGHLANDS_OF_NIGHTMARES,
            'continents' => [
                \App\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Territory::CLOUD_PIERCING_CLIFFS,
            'continents' => [
                \App\Continent::VINDOBERON,
                \App\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Territory::OUTER_RIM_OF_THE_DEMONIC,
            'continents' => [
                \App\Continent::DEMAUXOR
            ]
        ]
    ];

        foreach ($territories as $territory) {
            /** @var \App\Territory $territoryCreated */
            $territoryCreated = \App\Territory::create([
                'name' => $territory['name'],
            ]);

            $continentIDs = $continents->filter( function(\App\Continent $continent) use ($territory) {
               return in_array($continent->name, $territory['continents']);
            })->pluck('id')->toArray();

            $territoryCreated->continents()->attach($continentIDs);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Territory::truncate();
    }
}
