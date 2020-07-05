<?php

use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedMinionsA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $minions = collect([
            /*
             * Golems
             */
            [
                'name' => 'Amber Golem',
                'level' => 42,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 12
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Coral Golem',
                'level' => 56,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Malachite Golem',
                'level' => 60,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 16
                    ]
                ]
            ],
            [
                'name' => 'Turquoise Golem',
                'level' => 87,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp',
                    'Pummel'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Opal Golem',
                'level' => 109,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp',
                    'Pummel',
                    'Ground Slam'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 16
                    ]
                ]
            ],
            [
                'name' => 'Hematite Golem',
                'level' => 114,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp',
                    'Pummel',
                    'Ground Slam'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 28
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 18
                    ]
                ]
            ],


            /*
             * Imps
             */
            [
                'name' => 'Gray Imp',
                'level' => 8,
                'enemy_type' => EnemyType::IMP,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_1,
                        'count' => 1,
                        'chance' => 2
                    ]
                ]
            ],
            [
                'name' => 'Yellow Imp',
                'level' => 11,
                'enemy_type' => EnemyType::IMP,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2.1
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_1,
                        'count' => 1,
                        'chance' => 2.1
                    ]
                ]
            ],
            [
                'name' => 'Green Imp',
                'level' => 13,
                'enemy_type' => EnemyType::IMP,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2.2
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_1,
                        'count' => 1,
                        'chance' => 2.2
                    ]
                ]
            ],
            [
                'name' => 'Orange Imp',
                'level' => 15,
                'enemy_type' => EnemyType::IMP,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_LOW_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 2.3
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_1,
                        'count' => 1,
                        'chance' => 2.3
                    ]
                ]
            ],


            /*
             * Vampires
             */
            [
                'name' => 'Young Vampire',
                'level' => 34,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Pierce',
                    'Double Pierce'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => 'Vampire',
                'level' => 47,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Severe Bite',
                    'Slash',
                    'Double Slash',
                    'Blade Sweep'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 12
                    ]
                ]
            ],
            [
                'name' => 'Vampire Veteran',
                'level' => 63,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Severe Bite',
                    'Slice',
                    'Double Slice',
                    'Blade Sweep'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 12
                    ]
                ]
            ],
            [
                'name' => 'Vampire Magus',
                'level' => 87,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Severe Bite',
                    'Vicious Bite',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Triple Magic Bullet',
                    'Blood Swell'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Vampire Knight',
                'level' => 93,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Cleave',
                    'Double Cleave',
                    'Triple Cleave',
                    'Blade Sweep',
                    'Blade Whirlwind',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 22
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 16
                    ]
                ]
            ],

            /*
             * Liches
             */
            [
                'name' => 'Lich',
                'level' => 53,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                    'Triple Slash',
                    'Slice',
                    'Double Slice',
                    'Blade Sweep',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST,
                        'count' => 1,
                        'chance' => 5
                    ]
                ]
            ],
            [
                'name' => 'Lich Fighter',
                'level' => 72,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Double Slash',
                    'Triple Slash',
                    'Slice',
                    'Double Slice',
                    'Triple Slice',
                    'Blade Sweep',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST,
                        'count' => 1,
                        'chance' => 10
                    ]
                ]
            ],
            [
                'name' => 'Lich Archer',
                'level' => 76,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Triple Arrow Release',
                    'Arrow Shot',
                    'Arrow Spray',
                    'Arrow Assault'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_RANGER_CHEST,
                        'count' => 1,
                        'chance' => 11
                    ]
                ]
            ],
            [
                'name' => 'Lich Mage',
                'level' => 77,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Magic Dart',
                    'Double Magic Dart',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Magic Burst'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_SORCERER_CHEST,
                        'count' => 1,
                        'chance' => 12
                    ]
                ]
            ],
        ]);

        $attacks = Attack::all();

        $minions->each(function ($minionData) use ($attacks) {
            $count = count($minionData['attacks']);
            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($minionData) {
                return in_array($attack->name, $minionData['attacks']);
            });
            if ($count != $attacksToAttach->count() ) {

                $missing = collect($minionData['attacks'])->filter(function ($attackName) use ($attacksToAttach) {
                    $match = $attacksToAttach->first(function (Attack $attack) use ($attackName) {
                        return $attack->name === $attackName;
                    });
                    return is_null($match);
                })->first();
                throw new RuntimeException("Not all of the attacks for " . $minionData['name'] . " were found: " . $missing);
            }
        });

        $enemyTypes = EnemyType::all();
        $combatPositions = CombatPosition::all();
        $chestBlueprints = ChestBlueprint::all();

        $minions->each(function ($minionData) use ($enemyTypes, $combatPositions, $attacks, $chestBlueprints) {

            /** @var Minion $minion */
            $minion = Minion::query()->create([
                'uuid' => Str::uuid(),
                'name' => $minionData['name'],
                'level' => $minionData['level'],
                'enemy_type_id' => $enemyTypes->where('name', '=', $minionData['enemy_type'])->first()->id,
                'combat_position_id' => $combatPositions->where('name', '=', $minionData['combat_position'])->first()->id
            ]);

            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($minionData) {
                return in_array($attack->name, $minionData['attacks']);
            });
            $minion->attacks()->saveMany($attacksToAttach);

            foreach ($minionData['chest_blueprints'] as $blueprintData) {
                $chestBlueprintToAttach = $chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($blueprintData) {
                    return $chestBlueprint->reference_id === $blueprintData['reference_id'];
                });
                $minion->chestBlueprints()->save($chestBlueprintToAttach, [
                    'count' => $blueprintData['count'],
                    'chance' => $blueprintData['chance'],
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
