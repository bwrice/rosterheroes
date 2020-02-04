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
                'name' => 'Medium Skeleton Pack',
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
            ]
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
