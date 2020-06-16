<?php

use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedSideQuestBlueprintsA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sideQuestBlueprints = collect([
            [
                'name' => null,
                'reference_id' => 'AA',
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'count' => 2
                    ],
                    [
                        'name' => 'Coral Golem',
                        'count' => 1
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AB',
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'count' => 2
                    ],
                    [
                        'name' => 'Coral Golem',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AC',
                'minions' => [
                    [
                        'name' => 'Coral Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 18
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AD',
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'count' => 6
                    ],
                    [
                        'name' => 'Coral Golem',
                        'count' => 4
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 2,
                        'chance' => 18
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AE',
                'minions' => [
                    [
                        'name' => 'Malachite Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 4
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 7
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AF',
                'minions' => [
                    [
                        'name' => 'Malachite Golem',
                        'count' => 5
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 4
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 1
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 30
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 10
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AG',
                'minions' => [
                    [
                        'name' => 'Coral Golem',
                        'count' => 1
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'count' => 2
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 5
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 10
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AH',
                'minions' => [
                    [
                        'name' => 'Coral Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'count' => 4
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 6
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AI',
                'minions' => [
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 1
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 1
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'count' => 1
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 6
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AJ',
                'minions' => [
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 2
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AK',
                'minions' => [
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 4
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AL',
                'minions' => [
                    [
                        'name' => 'Turquoise Golem',
                        'count' => 5
                    ],
                    [
                        'name' => 'Opal Golem',
                        'count' => 6
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'count' => 4
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 3,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 10
                    ]
                ]
            ],

            /*
             * Undead mixtures
             */
            [
                'name' => null,
                'reference_id' => 'AM',
                'minions' => [
                    [
                        'name' => 'Lich',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AO',
                'minions' => [
                    [
                        'name' => 'Lich',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 4
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 8
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AP',
                'minions' => [
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],

            [
                'name' => null,
                'reference_id' => 'AQ',
                'minions' => [
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Captain',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],

            [
                'name' => null,
                'reference_id' => 'AR',
                'minions' => [
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 1
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 1
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 1
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ]
                ],
            ],
            [
                'name' => 'Musty Tomb',
                'reference_id' => 'AS',
                'minions' => [
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 5
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 4
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 2
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Decrepit Tomb',
                'reference_id' => 'AT',
                'minions' => [
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 5
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Captain',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 2
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'count' => 5
                    ],
                    [
                        'name' => 'Lich Archer',
                        'count' => 4
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],

            /*
             * Imps
             */
            [
                'name' => 'Imp Cluster',
                'reference_id' => 'AU',
                'minions' => [
                    [
                        'name' => 'Gray Imp',
                        'count' => 6
                    ],
                    [
                        'name' => 'Yellow Imp',
                        'count' => 5
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 4
                    ],
                    [
                        'name' => 'Orange Imp',
                        'count' => 4
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::SMALL_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 3
                    ]
                ]
            ],
            [
                'name' => 'Large Imp Cluster',
                'reference_id' => 'AV',
                'minions' => [
                    [
                        'name' => 'Gray Imp',
                        'count' => 8
                    ],
                    [
                        'name' => 'Yellow Imp',
                        'count' => 8
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 12
                    ],
                    [
                        'name' => 'Orange Imp',
                        'count' => 7
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::SMALL_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2
                    ]
                ]
            ],
            [
                'name' => 'Imp Swarm',
                'reference_id' => 'AW',
                'minions' => [
                    [
                        'name' => 'Gray Imp',
                        'count' => 14
                    ],
                    [
                        'name' => 'Yellow Imp',
                        'count' => 15
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 13
                    ],
                    [
                        'name' => 'Orange Imp',
                        'count' => 10
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2
                    ]
                ]
            ],
        ]);

        $minions = Minion::all();
        $chestBlueprints = ChestBlueprint::all();

        $sideQuestBlueprints->each(function ($sideQuestBlueprintData) use ($minions) {
            $minionNames = collect($sideQuestBlueprintData['minions'])->map(function ($sideQuestMinion) {
                return $sideQuestMinion['name'];
            });
            $minionsToAttach = $minions->whereIn('name', $minionNames->values()->toArray());
            if ($minionsToAttach->count() != $minionNames->count()) {
                throw new RuntimeException("Cannot find all the minions for side-quest: " . $sideQuestBlueprintData['reference_id']);
            }
        });

        $sideQuestBlueprints->each(function ($sideQuestBlueprintData) use ($minions, $chestBlueprints) {
            /** @var SideQuest $sideQuestBlueprint */
            $sideQuestBlueprint = SideQuestBlueprint::query()->create([
                'name' => $sideQuestBlueprintData['name'],
                'reference_id' => $sideQuestBlueprintData['reference_id']
            ]);
            $minionAttachArrays = collect($sideQuestBlueprintData['minions'])->map(function ($sideQuestMinion) use ($minions) {
                $minionName = $sideQuestMinion['name'];
                return [
                    'minion' => $minions->first(function(Minion $minion) use ($minionName) {
                        return $minion->name === $minionName;
                    }),
                    'count' => $sideQuestMinion['count']
                ];
            });

            $minionAttachArrays->each(function ($attachArray) use ($sideQuestBlueprint) {
                $sideQuestBlueprint->minions()->save($attachArray['minion'], ['count' => $attachArray['count']]);
            });


            foreach ($sideQuestBlueprintData['chest_blueprints'] as $chestBlueprintData) {
                $chestBlueprintToAttach = $chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($chestBlueprintData) {
                    return $chestBlueprint->reference_id === $chestBlueprintData['reference_id'];
                });

                $sideQuestBlueprint->chestBlueprints()->save($chestBlueprintToAttach, [
                    'count' => $chestBlueprintData['count'],
                    'chance' => $chestBlueprintData['chance'],
                ]);
            }
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
