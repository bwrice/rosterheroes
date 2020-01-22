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
                'config_path' => '/Yaml/Attacks/slash.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_slash.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Triple Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_slash.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Sword Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/sword_sweep.yaml',
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ],
            ],
            [
                'name' => 'Axe Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/axe_sweep.yaml',
                'item_bases' => [
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Blade Spin',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/blade_spin.yaml',
                'item_bases' => [
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/slice.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Double Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_slice.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Triple Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_slice.yaml',
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::POLEARM,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/whack.yaml',
                'item_bases' => [
                    ItemBase::MACE,
                ],
            ],
            [
                'name' => 'Double Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_whack.yaml',
                'item_bases' => [
                    ItemBase::MACE,
                ]
            ],
            [
                'name' => 'Triple Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_whack.yaml',
                'item_bases' => [
                    ItemBase::MACE,
                ]
            ],
            [
                'name' => 'Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/poke.yaml',
                'item_bases' => [
                    ItemBase::POLEARM,
                ]
            ],
            [
                'name' => 'Double Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_poke.yaml',
                'item_bases' => [
                    ItemBase::POLEARM,
                ]
            ],
            [
                'name' => 'Triple Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_poke.yaml',
                'item_bases' => [
                    ItemBase::POLEARM,
                ]
            ],
            [
                'name' => Attack::BASIC_BOW_ATTACK_NAME, // Arrow
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/arrow.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                ]
            ],
            [
                'name' => 'Double Arrow',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_arrow.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                ]
            ],
            [
                'name' => 'Triple Arrow',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_arrow.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                ]
            ],
            [
                'name' => 'Arrow Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/arrow_spray.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                ]
            ],
            [
                'name' => 'Deep Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/deep_shot.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Long Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/long_shot.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Double Long Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_long_shot.yaml',
                'item_bases' => [
                    ItemBase::BOW,
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/bolt.yaml',
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ]
            ],
            [
                'name' => 'Double Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_bolt.yaml',
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ]
            ],
            [
                'name' => 'Triple Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_bolt.yaml',
                'item_bases' => [
                    ItemBase::CROSSBOW,
                ]
            ],
            [
                'name' => 'Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/chuck.yaml',
                'item_bases' => [
                    ItemBase::THROWING_WEAPON,
                ]
            ],
            [
                'name' => Attack::BASIC_MAGIC_ATTACK_NAME, // Magic Bolt
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_bolt.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Double Magic Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_bolt.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Triple Magic Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_bolt.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Magic Burst',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_burst.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Magic Blast',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_blast.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Lightning Bolt',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/lightning_bolt.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::ORB
                ]
            ],
            [
                'name' => 'Lightning Strike',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/lightning_strike.yaml',
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::ORB
                ]
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
                'config_path' => $attackData['config_path'],
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
