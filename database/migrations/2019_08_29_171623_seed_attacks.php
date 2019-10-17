<?php

use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedAttacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attacks = [
            [
                'name' => Attack::BASIC_BLADE_ATTACK_NAME, // Slash
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/12,
                'base_damage_rating' => 5.5,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 3
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 8,
                'speed_rating' => 100/12,
                'base_damage_rating' => 5.5,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 7
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 12,
                'speed_rating' => 100/12,
                'base_damage_rating' => 5.5,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 16
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 15,
                'speed_rating' => 100/15,
                'base_damage_rating' => 12,
                'damage_multiplier_rating' => 18,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 5
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 21,
                'speed_rating' => 100/15,
                'base_damage_rating' => 12,
                'damage_multiplier_rating' => 18,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 11
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 28,
                'speed_rating' => 100/15,
                'base_damage_rating' => 12,
                'damage_multiplier_rating' => 18,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 23
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/16,
                'base_damage_rating' => 6,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::MACE,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 5
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 8,
                'speed_rating' => 100/16,
                'base_damage_rating' => 6,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::MACE,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 9
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 12,
                'speed_rating' => 100/16,
                'base_damage_rating' => 6,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::MACE,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 17
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/12,
                'base_damage_rating' => 4,
                'damage_multiplier_rating' => 11,
                'item_bases' => [
                    ItemBase::POLEARM,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 4
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 8,
                'speed_rating' => 100/12,
                'base_damage_rating' => 4,
                'damage_multiplier_rating' => 11,
                'item_bases' => [
                    ItemBase::POLEARM,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 7
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 12,
                'speed_rating' => 100/12,
                'base_damage_rating' => 4,
                'damage_multiplier_rating' => 11,
                'item_bases' => [
                    ItemBase::POLEARM,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 12
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => Attack::BASIC_BOW_ATTACK_NAME, // Arrow
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/23,
                'base_damage_rating' => 10,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::BOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 7
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Arrow',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 9,
                'speed_rating' => 100/23,
                'base_damage_rating' => 10,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::BOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 12
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Arrow',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 16,
                'speed_rating' => 100/23,
                'base_damage_rating' => 10,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::BOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 19
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/30,
                'base_damage_rating' => 11,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 8
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 8,
                'speed_rating' => 100/30,
                'base_damage_rating' => 11,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 13
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 15,
                'speed_rating' => 100/30,
                'base_damage_rating' => 11,
                'damage_multiplier_rating' => 13,
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 21
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/28,
                'base_damage_rating' => 9,
                'damage_multiplier_rating' => 11,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON,
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 6
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => Attack::BASIC_MAGIC_ATTACK_NAME, // Magic Bolt
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 5,
                'speed_rating' => 100/18,
                'base_damage_rating' => 8,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 2
                    ],
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::MANA,
                        'amount' => 1
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Double Magic Bolt', // Magic Bolt
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 2,
                'grade' => 8,
                'speed_rating' => 100/18,
                'base_damage_rating' => 8,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 4
                    ],
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::MANA,
                        'amount' => 3
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Triple Magic Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 3,
                'grade' => 15,
                'speed_rating' => 100/18,
                'base_damage_rating' => 8,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 5
                    ],
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::MANA,
                        'amount' => 7
                    ]
                ],
                'requirements' => []
            ],
            [
                'name' => 'Magic Blast',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'fixed_target_count' => 1,
                'grade' => 9,
                'speed_rating' => 100/45,
                'base_damage_rating' => 8,
                'damage_multiplier_rating' => 10,
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ],
                'resource_costs' => [
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::STAMINA,
                        'amount' => 6
                    ],
                    [
                        'type' => ResourceCost::FIXED,
                        'resource' => MeasurableType::MANA,
                        'amount' => 10
                    ]
                ],
                'requirements' => []
            ],
        ];

        $damageTypes = DamageType::all();
        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();
        $itemBases = ItemBase::all();

        foreach($attacks as $attackData) {
            /** @var Attack $attack */
            $attack = Attack::query()->create([
                'name' => $attackData['name'],
                'damage_type_id' => $damageTypes->firstWhere('name', '=', $attackData['damage_type'])->id,
                'attacker_position_id' => $combatPositions->firstWhere('name', '=', $attackData['attacker_position'])->id,
                'target_position_id' => $combatPositions->firstWhere('name', '=', $attackData['target_position'])->id,
                'target_priority_id' => $targetPriorities->firstWhere('name', '=', $attackData['target_priority'])->id,
                'grade' => $attackData['grade'],
                'speed_rating' => $attackData['speed_rating'],
                'base_damage_rating' => $attackData['base_damage_rating'],
                'damage_multiplier_rating' => $attackData['damage_multiplier_rating'],
                'fixed_target_count' => isset($attackData['fixed_target_count']) ? $attackData['fixed_target_count'] : 1,
                'resource_costs' => json_encode($attackData['resource_costs']),
                'requirements' => json_encode($attackData['requirements'])
            ]);

            $basesToAttach = $itemBases->whereIn('name', $attackData['item_bases']);
            $attack->itemBases()->saveMany($basesToAttach);
        }
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
