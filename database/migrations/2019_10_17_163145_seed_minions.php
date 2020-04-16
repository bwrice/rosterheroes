<?php

use App\ChestBlueprint;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class SeedMinions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $minions = collect([
            [
                'name' => 'Skeleton Scout',
                'level' => 8,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Slash',
                    'Arrow Release',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Guard',
                'level' => 13,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Archer',
                'level' => 17,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Arrow Spray',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Mage',
                'level' => 18,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Magic Dart',
                    'Double Magic Dart',
                    'Magic Burst'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Soldier',
                'level' => 21,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                    'Triple Slash',
                    'Blade Sweep',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Marksman',
                'level' => 23,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Triple Arrow Release',
                    'Arrow Spray',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_RANGER_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Battler',
                'level' => 31,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Pierce',
                    'Double Pierce',
                    'Stab',
                    'Double Stab',
                    'Triple Stab',
                    'Polearm Strike',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST,
                        'count' => 1
                    ]
                ]
            ],
            [
                'name' => 'Skeleton Captain',
                'level' => 40,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Smash',
                    'Double Smash',
                    'Triple Smash',
                    'Clobber',
                    'Double Clobber',
                    'Mace Sweep',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST,
                        'count' => 1
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Young Werewolf',
                'level' => 15,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf',
                'level' => 21,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf Thrasher',
                'level' => 28,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf Mangler',
                'level' => 37,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf Ravager',
                'level' => 45,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST,
                        'count' => 1
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf Mauler',
                'level' => 51,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST,
                        'count' => 1
                    ],
                    [
                        'reference_id' => ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST,
                        'count' => 1
                    ],
                ]
            ],
            [
                'name' => 'Werewolf Maimer',
                'level' => 59,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [

                ]
            ],
            [
                'name' => 'Werewolf Eviscerator',
                'level' => 90,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ],
                'chest_blueprints' => [

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

            $name = $minionData['name'];

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

            $chestBlueprintsToAttach = $chestBlueprints->filter(function (ChestBlueprint $chestBlueprint) use ($minionData) {
                return in_array($chestBlueprint->reference_id, $minionData['chest_blueprints']);
            });
//            $minion->chestBlueprints()->saveMany($chestBlueprintsToAttach);
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
