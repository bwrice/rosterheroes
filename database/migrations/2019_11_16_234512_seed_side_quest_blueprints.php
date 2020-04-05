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
                'name' => 'Skeleton Scout',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Guard',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Archer',
                'minions' => [
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Mage',
                'minions' => [
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Small Skeleton Pack',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 2
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Pack',
                'minions' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 2
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
                    ],
                ]
            ],
            [
                'name' => 'Large Skeleton Pack',
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 4
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ],
                ]
            ],
            [
                'name' => 'Werewolf',
                'minions' => [
                    [
                        'name' => 'Werewolf',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Thrasher',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Mangler',
                'minions' => [
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Ravager',
                'minions' => [
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Mauler',
                'minions' => [
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Maimer',
                'minions' => [
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Eviscerator',
                'minions' => [
                    [
                        'name' => 'Werewolf Eviscerator',
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Small Werewolf Cluster',
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
                'name' => 'Werewolf Cluster',
                'minions' => [
                    [
                        'name' => 'Werewolf',
                        'count' => 7
                    ],
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
                'name' => 'Large Werewolf Cluster',
                'minions' => [
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 5
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 5
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
                'name' => 'Werewolf Hunting Pack',
                'minions' => [
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
                        'count' => 6
                    ]
                ]
            ],
            [
                'name' => 'Werewolf Predator Pack',
                'minions' => [
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 6
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 8
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 5
                    ],
                    [
                        'name' => 'Werewolf Eviscerator',
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
                throw new RuntimeException("Cannot find all the minions for side-quest: " . $sideQuestBlueprintData['name']);
            }
        });

        $sideQuestBlueprints->each(function ($sideQuestBlueprintData) use ($minions) {
            /** @var SideQuest $sideQuest */
            $sideQuest = SideQuestBlueprint::query()->create([
                'name' => $sideQuestBlueprintData['name']
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
