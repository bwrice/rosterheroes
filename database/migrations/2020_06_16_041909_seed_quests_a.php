<?php

use App\Domain\Actions\CreateSideQuest;
use App\Domain\Models\Minion;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuestBlueprint;
use App\Domain\Models\Titan;
use App\Domain\Models\TravelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedQuestsA extends Migration
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
                'name' => 'Icros Golem Quarry',
                'travel_type' => TravelType::STATIONARY,
                'level' => 284,
                'starting_province' => 'Icros',
                'titans' => [
                    [
                        'name' => 'Empyrean Golem',
                        'count' => 4
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Coral Golem',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'weight' => 12
                    ],
                    [
                        'name' => 'Opal Golem',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'weight' => 8
                    ],
                ],
                'side_quest_blueprints' => [
                    'AA',
                    'AB',
                    'AC',
                    'AD',
                    'AE',
                    'AG',
                    'AH',
                    'AI',
                    'AK',
                    'AL'
                ]
            ],
            [
                'name' => 'Zotras Golem Quarry',
                'travel_type' => TravelType::STATIONARY,
                'level' => 341,
                'starting_province' => 'Zotras',
                'titans' => [
                    [
                        'name' => 'Empyrean Golem',
                        'count' => 5
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Coral Golem',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'weight' => 12
                    ],
                    [
                        'name' => 'Opal Golem',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'weight' => 8
                    ],
                ],
                'side_quest_blueprints' => [
                    'AA',
                    'AB',
                    'AD',
                    'AF',
                    'AE',
                    'AG',
                    'AH',
                    'AJ',
                    'AK',
                    'AL'
                ]
            ],
            [
                'name' => 'Tykria Golem Quarry',
                'travel_type' => TravelType::STATIONARY,
                'level' => 402,
                'starting_province' => 'Tykria',
                'titans' => [
                    [
                        'name' => 'Empyrean Golem',
                        'count' => 7
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Amber Golem',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Coral Golem',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Malachite Golem',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Turquoise Golem',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Opal Golem',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Hematite Golem',
                        'weight' => 20
                    ],
                ],
                'side_quest_blueprints' => [
                    'AB',
                    'AC',
                    'AD',
                    'AE',
                    'AF',
                    'AG',
                    'AH',
                    'AI',
                    'AJ',
                    'AK',
                    'AL'
                ]
            ],

            [
                'name' => 'Caves of Esparax',
                'travel_type' => TravelType::STATIONARY,
                'level' => 214,
                'starting_province' => 'Esparax',
                'titans' => [
                    [
                        'name' => 'Lich Baron',
                        'count' => 4
                    ],
                    [
                        'name' => 'Lich Overlord',
                        'count' => 1
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Yellow Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Green Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Orange Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Lich',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Lich Archer',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Lich Mage',
                        'weight' => 15
                    ],
                ],
                'side_quest_blueprints' => [
                    'D',
                    'F',
                    'H',
                    'K',
                    'M',
                    'N',
                    'AM',
                    'AO',
                    'AP',
                    'AQ',
                    'AR',
                    'AS',
                    'AT',
                    'AU',
                    'AV',
                ]
            ],
            [
                'name' => 'Wesnurg Catacombs',
                'travel_type' => TravelType::STATIONARY,
                'level' => 255,
                'starting_province' => 'Wesnurg',
                'titans' => [
                    [
                        'name' => 'Lich Baron',
                        'count' => 5
                    ],
                    [
                        'name' => 'Lich Overlord',
                        'count' => 2
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Yellow Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Green Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Orange Imp',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Lich',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Lich Archer',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Lich Mage',
                        'weight' => 15
                    ],
                ],
                'side_quest_blueprints' => [
                    'D',
                    'H',
                    'K',
                    'N',
                    'AM',
                    'AO',
                    'AQ',
                    'AR',
                    'AS',
                    'AT',
                    'AU',
                    'AV',
                    'AW',
                ]
            ],
            [
                'name' => 'Ruins of Dodrurg',
                'travel_type' => TravelType::STATIONARY,
                'level' => 287,
                'starting_province' => 'Dodrurg',
                'titans' => [
                    [
                        'name' => 'Lich Baron',
                        'count' => 7
                    ],
                    [
                        'name' => 'Lich Overlord',
                        'count' => 2
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Green Imp',
                        'weight' => 12
                    ],
                    [
                        'name' => 'Orange Imp',
                        'weight' => 12
                    ],
                    [
                        'name' => 'Lich',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Lich Fighter',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Lich Archer',
                        'weight' => 25
                    ],
                    [
                        'name' => 'Lich Mage',
                        'weight' => 21
                    ],
                ],
                'side_quest_blueprints' => [
                    'F',
                    'H',
                    'L',
                    'M',
                    'N',
                    'AO',
                    'AP',
                    'AQ',
                    'AR',
                    'AS',
                    'AT',
                    'AU',
                    'AV',
                    'AW',
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

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('reference_id', $questData['side_quest_blueprints']);
            if ($blueprintsForQuest->count() != count($questData['side_quest_blueprints'])) {

                $missing = collect($questData['side_quest_blueprints'])->reject(function ($name) use ($sideQuestBlueprints) {
                    return in_array($name, $sideQuestBlueprints->pluck('name')->toArray());
                });
                throw new RuntimeException("Couldn't find all the side-quests for quest: " . $questData['name'] . ' : ' . print_r($missing, true));
            }
        });

        $quests->each(function ($questData) use ($provinces) {
            $province = $provinces->firstWhere('name', $questData['starting_province']);
            if (! $province) {
                throw new RuntimeException("Couldn't find province for quest: " . $questData['name']);
            }
        });

        /** @var CreateSideQuest $createSideQuestAction */
        $createSideQuestAction = app(CreateSideQuest::class);
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

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('reference_id', $questData['side_quest_blueprints']);
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
