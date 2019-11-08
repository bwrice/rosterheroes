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
        $continents = \App\Domain\Models\Continent::all();

        $territories = [
        [
            'name' => \App\Domain\Models\Territory::GARDENS_OF_REDEMPTION,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::WOODS_OF_THE_WILD,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::TWISTING_ISLES_OF_ILLUSIONS,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::GRASSLANDS_OF_GIANTS,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA,
                \App\Domain\Models\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::MENACING_MARCHES,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA,
                \App\Domain\Models\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::TROPICS_OF_TREPIDATION,
            'continents' => [
                \App\Domain\Models\Continent::FETROYA,
                \App\Domain\Models\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::PERILOUS_PLAINS,
            'continents' => [
                \App\Domain\Models\Continent::EAST_WOZUL,
                \App\Domain\Models\Continent::WEST_WOZUL,
                \App\Domain\Models\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::TREACHEROUS_FOREST,
            'continents' => [
                \App\Domain\Models\Continent::NORTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::SAVAGE_SWAMPS,
            'continents' => [
                \App\Domain\Models\Continent::EAST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::DESERT_OF_DESPAIR,
            'continents' => [
                \App\Domain\Models\Continent::EAST_WOZUL,
                \App\Domain\Models\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::HUMBLING_HILLS,
            'continents' => [
                \App\Domain\Models\Continent::NORTH_JAGONETH,
                \App\Domain\Models\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::GULF_OF_SERPENTS,
            'continents' => [
                \App\Domain\Models\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::CARNIVOROUS_CANYONS,
            'continents' => [
                \App\Domain\Models\Continent::WEST_WOZUL
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::INFERNAL_ISLANDS,
            'continents' => [
                \App\Domain\Models\Continent::WEST_WOZUL,
                \App\Domain\Models\Continent::CENTRAL_JAGONETH,
                \App\Domain\Models\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::JADE_JUNGLE,
            'continents' => [
                \App\Domain\Models\Continent::NORTH_JAGONETH,
                \App\Domain\Models\Continent::CENTRAL_JAGONETH,
                \App\Domain\Models\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::SHORES_OF_THE_SHADOWS,
            'continents' => [
                \App\Domain\Models\Continent::NORTH_JAGONETH,
                \App\Domain\Models\Continent::CENTRAL_JAGONETH,
                \App\Domain\Models\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::VALLEY_OF_VANISHINGS,
            'continents' => [
                \App\Domain\Models\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::PRIMAL_PASSAGE,
            'continents' => [
                \App\Domain\Models\Continent::WEST_WOZUL,
                \App\Domain\Models\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::ARCTIC_ARCHIPELAGO,
            'continents' => [
                \App\Domain\Models\Continent::NORTH_JAGONETH,
                \App\Domain\Models\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::BADLANDS_OF_THE_LEVIATHAN,
            'continents' => [
                \App\Domain\Models\Continent::CENTRAL_JAGONETH,
                \App\Domain\Models\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::PEAKS_OF_PANDORA,
            'continents' => [
                \App\Domain\Models\Continent::CENTRAL_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::CRYPTIC_COAST,
            'continents' => [
                \App\Domain\Models\Continent::CENTRAL_JAGONETH,
                \App\Domain\Models\Continent::SOUTH_JAGONETH,
                \App\Domain\Models\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::WETLANDS_OF_WANDERING_TORMENT,
            'continents' => [
                \App\Domain\Models\Continent::SOUTH_JAGONETH
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::BLOODSTONE_BEACHES,
            'continents' => [
                \App\Domain\Models\Continent::SOUTH_JAGONETH,
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::TURBULENT_TUNDRA,
            'continents' => [
                \App\Domain\Models\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::COVES_OF_CORRUPTION,
            'continents' => [
                \App\Domain\Models\Continent::SOUTH_JAGONETH,
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::MOUNTAINS_OF_MISERY,
            'continents' => [
                \App\Domain\Models\Continent::VINDOBERON
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::ENCLAVE_OF_ENIGMAS,
            'continents' => [
                \App\Domain\Models\Continent::SOUTH_JAGONETH,
                \App\Domain\Models\Continent::VINDOBERON,
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::SCREAMING_HIGHLANDS_OF_NIGHTMARES,
            'continents' => [
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::CLOUD_PIERCING_CLIFFS,
            'continents' => [
                \App\Domain\Models\Continent::VINDOBERON,
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ],
        [
            'name' => \App\Domain\Models\Territory::OUTER_RIM_OF_THE_DEMONIC,
            'continents' => [
                \App\Domain\Models\Continent::DEMAUXOR
            ]
        ]
    ];

        foreach ($territories as $territory) {
            /** @var \App\Domain\Models\Territory $territoryCreated */
            $territoryCreated = \App\Domain\Models\Territory::create([
                'name' => $territory['name'],
            ]);

            $continentIDs = $continents->filter( function(\App\Domain\Models\Continent $continent) use ($territory) {
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
        \App\Domain\Models\Territory::truncate();
    }
}
