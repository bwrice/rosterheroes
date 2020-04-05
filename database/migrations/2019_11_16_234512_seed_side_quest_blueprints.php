<?php

use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestBlueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class SeedSideQuestBlueprints extends Migration
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
                'reference_id' => 'A',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'B',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'C',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'D',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'E',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'F',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'G',
                'minions' => [
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 1
                    ],

                ]
            ],
            [
                'name' => null,
                'reference_id' => 'H',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'I',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 5
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'J',
                'minions' => [
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 3
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'K',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 7
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'L',
                'minions' => [
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'M',
                'minions' => [
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Platoon',
                'reference_id' => 'N',
                'minions' => [
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 9
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'count' => 7
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 5
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Captain',
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'O',
                'minions' => [
                    [
                        'name' => 'Werewolf',
                        'count' => 3
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'P',
                'minions' => [
                    [
                        'name' => 'Young Werewolf',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'Q',
                'minions' => [
                    [
                        'name' => 'Young Werewolf',
                        'count' => 1
                    ],
                    [
                        'name' => 'Werewolf',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'R',
                'minions' => [
                    [
                        'name' => 'Werewolf',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 3
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'S',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 1
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'T',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'U',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 1
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 1
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'V',
                'minions' => [
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'W',
                'minions' => [
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Hunting Pack',
                'reference_id' => 'X',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 7
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 5
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Eviscerator',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Predator Pack',
                'reference_id' => 'Y',
                'minions' => [
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 7
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 8
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 6
                    ],
                    [
                        'name' => 'Werewolf Eviscerator',
                        'count' => 3
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'Z',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 4
                    ]
                ]
            ],
        ]);

        $minions = Minion::all();

        $sideQuestBlueprints->each(function ($sideQuestBlueprintData) use ($minions) {
            $minionNames = collect($sideQuestBlueprintData['minions'])->map(function ($sideQuestMinion) {
                return $sideQuestMinion['name'];
            });
            $minionsToAttach = $minions->whereIn('name', $minionNames->values()->toArray());
            if ($minionsToAttach->count() != $minionNames->count()) {
                throw new RuntimeException("Cannot find all the minions for side-quest: " . $sideQuestBlueprintData['reference_id']);
            }
        });

        $sideQuestBlueprints->each(function ($sideQuestBlueprintData) use ($minions) {
            /** @var SideQuest $sideQuest */
            $sideQuest = SideQuestBlueprint::query()->create([
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

            $minionAttachArrays->each(function ($attachArray) use ($sideQuest) {
                $sideQuest->minions()->save($attachArray['minion'], ['count' => $attachArray['count']]);
            });
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
