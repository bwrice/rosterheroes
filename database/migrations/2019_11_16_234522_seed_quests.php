<?php

use App\Domain\Actions\CreateSkirmishAction;
use App\Domain\Models\Minion;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\SkirmishBlueprint;
use App\Domain\Models\Titan;
use App\Domain\Models\TravelType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class SeedQuests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $quests = collect([
            [
                'name' => 'North Skeleton Fortress',
                'travel_type' => TravelType::STATIONARY,
                'level' => 125,
                'starting_province' => 'Everium',
                'titans' => [
                    [
                        'name' => 'Skeleton Overlord',
                        'count' => 1
                    ],
                    [
                        'name' => 'Skeleton General',
                        'count' => 4
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Skeleton Guard',
                        'weight' => 35
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Skeleton Marksman',
                        'weight' => 10
                    ],
                ],
                'skirmish_blueprints' => [
                    'Small Skeleton Pack',
                    'Medium Skeleton Pack',
                    'Large Skeleton Pack'
                ]

            ]
        ]);

        $minions = Minion::all();
        $titans = Titan::all();
        $skirmishBlueprints = SkirmishBlueprint::all();
        $provinces = Province::all();
        $travelTypes = TravelType::all();

        $quests->each(function ($questData) {
            $weightSum = collect($questData['minions'])->sum(function ($minionData) {
                return $minionData['weight'];
            });
            if ($weightSum != 100) {
                throw new RuntimeException("Minion weight of " . $weightSum . " does not equal 100 for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($minions) {
            $minionNames = array_map(function ($minionData) {
                return $minionData['name'];
            }, $questData['minions']);

            $minionsToAttach = $minions->whereIn('name', $minionNames);
            if ($minionsToAttach->count() != count($minionNames)) {
                throw new RuntimeException("Couldn't find all the minions for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($titans) {
            $titanNames = array_map(function ($minionData) {
                return $minionData['name'];
            }, $questData['titans']);

            $titansToAttach = $titans->whereIn('name', $titanNames);
            if ($titansToAttach->count() != count($titanNames)) {
                throw new RuntimeException("Couldn't find all the titans for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($skirmishBlueprints) {

            $blueprintsForQuest = $skirmishBlueprints->whereIn('name', $questData['skirmish_blueprints']);
            if ($blueprintsForQuest->count() != count($questData['skirmish_blueprints'])) {
                throw new RuntimeException("Couldn't find all the skirmishes for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($provinces) {
            $province = $provinces->firstWhere('name', $questData['starting_province']);
            if (! $province) {
                throw new RuntimeException("Couldn't find province for quest: " . $questData['name']);
            }
        });

        /** @var CreateSkirmishAction $createSkirmishAction */
        $createSkirmishAction = app(CreateSkirmishAction::class);
        $quests->each(function ($questData) use ($skirmishBlueprints, $minions, $titans, $provinces, $travelTypes, $createSkirmishAction) {

            /** @var Quest $quest */
            $quest = Quest::query()->create([
                'uuid' => Str::uuid(),
                'name' => $questData['name'],
                'level' => $questData['level'],
                'percent' => 100,
                'province_id' => $provinces->firstWhere('name', $questData['starting_province'])->id,
                'travel_type_id' => $travelTypes->firstWhere('name', $questData['travel_type'])->id,
            ]);

            collect($questData['minions'])->each(function ($minionData) use ($quest, $minions) {
                $minion = $minions->firstWhere('name', $minionData['name']);
                $quest->minions()->save($minion, ['weight' => $minionData['weight']]);
            });

            collect($questData['titans'])->each(function ($titanData) use ($quest, $titans) {
                $titan = $titans->firstWhere('name', $titanData['name']);
                $quest->titans()->save($titan, ['count' => $titanData['count']]);
            });

            $blueprintsForQuest = $skirmishBlueprints->whereIn('name', $questData['skirmish_blueprints']);
            $blueprintsForQuest->each(function (SkirmishBlueprint $skirmishBlueprint) use ($quest, $createSkirmishAction) {
                $createSkirmishAction->execute($skirmishBlueprint, $quest);
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
