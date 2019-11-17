<?php

use App\Domain\Models\Minion;
use App\Domain\Models\Skirmish;
use App\Domain\Models\SkirmishBlueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class SeedSkirmishBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $skirmishBlueprints = collect([
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
                        'name' => 'Skeleton Scout',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 2
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

        $skirmishBlueprints->each(function ($skirmishData) use ($minions) {
            $minionNames = collect($skirmishData['minions'])->map(function ($skirmishMinion) {
                return $skirmishMinion['name'];
            });
            $minionsToAttach = $minions->whereIn('name', $minionNames->values()->toArray());
            if ($minionsToAttach->count() != $minionNames->count()) {
                throw new RuntimeException("Cannot find all the minions for skirmish: " . $skirmishData['name']);
            }
        });

        $skirmishBlueprints->each(function ($skirmishData) use ($minions) {
            /** @var Skirmish $skirmish */
            $skirmish = SkirmishBlueprint::query()->create([
                'name' => $skirmishData['name']
            ]);
            $minionAttachArrays = collect($skirmishData['minions'])->map(function ($skirmishMinion) use ($minions) {
                $minionName = $skirmishMinion['name'];
                return [
                    'minion' => $minions->first(function(Minion $minion) use ($minionName) {
                        return $minion->name === $minionName;
                    }),
                    'count' => $skirmishMinion['count']
                ];
            });

            $minionAttachArrays->each(function ($attachArray) use ($skirmish) {
                $skirmish->minions()->save($attachArray['minion'], ['count' => $attachArray['count']]);
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
