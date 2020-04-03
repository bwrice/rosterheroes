<?php

use App\Domain\Actions\CreateSideQuestAction;
use App\Domain\Models\Minion;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestBlueprint;
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
                'name' => 'Everium Skeleton Fortress',
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
                'sid_quest_blueprints' => [
                    'Small Skeleton Pack',
                    'Skeleton Pack',
                    'Large Skeleton Pack'
                ]

            ],
            [
                'name' => 'Oberon Werewolf Pack',
                'travel_type' => TravelType::TERRITORY,
                'level' => 178,
                'starting_province' => 'Vyspen',
                'titans' => [
                    [
                        'name' => 'Oberon Pack Leader',
                        'count' => 1
                    ],
                    [
                        'name' => 'Werewolf Alpha',
                        'count' => 5
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Werewolf Young',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Werewolf Thrasher',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'weight' => 15
                    ],
                ],
                'sid_quest_blueprints' => [
                    'Werewolf Thrasher',
                    'Werewolf Mangler',
                    'Werewolf Maimer',
                    'Small Werewolf Cluster',
                    'Werewolf Cluster',
                    'Large Werewolf Cluster',
                    'Werewolf Hunting Pack'
                ]

            ],
        ]);

        $minions = Minion::all();
        $titans = Titan::all();
        $sideQuestBlueprints = SideQuestBlueprint::all();
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

        $quests->each(function ($questData) use ($sideQuestBlueprints) {

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('name', $questData['sid_quest_blueprints']);
            if ($blueprintsForQuest->count() != count($questData['sid_quest_blueprints'])) {
                throw new RuntimeException("Couldn't find all the side-quests for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($provinces) {
            $province = $provinces->firstWhere('name', $questData['starting_province']);
            if (! $province) {
                throw new RuntimeException("Couldn't find province for quest: " . $questData['name']);
            }
        });

        /** @var CreateSideQuestAction $createSideQuestAction */
        $createSideQuestAction = app(CreateSideQuestAction::class);
        $quests->each(function ($questData) use ($sideQuestBlueprints, $minions, $titans, $provinces, $travelTypes, $createSideQuestAction) {

            $provinceID = $provinces->firstWhere('name', $questData['starting_province'])->id;

            /** @var Quest $quest */
            $quest = Quest::query()->create([
                'uuid' => Str::uuid(),
                'name' => $questData['name'],
                'level' => $questData['level'],
                'percent' => 100,
                'province_id' => $provinceID,
                'initial_province_id' => $provinceID,
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

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('name', $questData['sid_quest_blueprints']);
            $blueprintsForQuest->each(function (SideQuestBlueprint $sideQuestBlueprint) use ($quest, $createSideQuestAction) {
                $createSideQuestAction->execute($sideQuestBlueprint, $quest);
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
