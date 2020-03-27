<?php

use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\TargetPriority;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

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
                'name' => 'Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Triple Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Pierce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Double Pierce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Triple Pierce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Double Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Triple Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Double Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Triple Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Double Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Triple Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Double Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Triple Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Double Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Triple Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Singe',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Double Singe',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Triple Singe',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Double Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Triple Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Double Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Triple Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Double Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Triple Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Double Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Triple Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Double Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Triple Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Double Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Triple Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Double Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Triple Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Sear',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Double Sear',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Triple Sear',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Impale',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Double Impale',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Triple Impale',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Double Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Triple Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE
                ]
            ],
            [
                'name' => 'Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Double Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Triple Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Double Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Triple Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Double Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Triple Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Double Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Triple Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Double Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Triple Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Scorch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Double Scorch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Triple Scorch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
                'item_bases' => [
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Blade Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Mace Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Polearm Strike',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Arrow Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Burst',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Throwing Shower',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Blade Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Mace Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Polearm Blitz',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Arrow Assault',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Barrage',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Blast',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Throwing Downpour',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],
            [
                'name' => 'Blade Tornado',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::DAGGER,
                    ItemBase::SWORD,
                    ItemBase::AXE,
                    ItemBase::TWO_HAND_SWORD,
                    ItemBase::TWO_HAND_AXE,
                    ItemBase::PSIONIC_ONE_HAND,
                    ItemBase::PSIONIC_TWO_HAND
                ]
            ],
            [
                'name' => 'Mace Tornado',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::MACE
                ]
            ],
            [
                'name' => 'Polearm Onslaught',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::POLEARM
                ]
            ],
            [
                'name' => 'Hail of Arrows',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::BOW
                ]
            ],
            [
                'name' => 'Bolt Bombardment',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::CROSSBOW
                ]
            ],
            [
                'name' => 'Magic Storm',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::WAND,
                    ItemBase::ORB,
                    ItemBase::STAFF
                ]
            ],
            [
                'name' => 'Throwing Torrent',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => null,
                'item_bases' => [
                    ItemBase::THROWING_WEAPON
                ]
            ],

            // TODO: unique attacks
//            [
//                'name' => 'Assassinate',
//                'damage_type' => DamageType::FIXED_TARGET,
//                'attacker_position' => CombatPosition::BACK_LINE,
//                'target_position' => CombatPosition::FRONT_LINE,
//                'target_priority' => TargetPriority::ANY,
//                'tier' => 4,
//                'targets_count' => 1,
//                'item_bases' => [
//                    ItemBase::DAGGER
//                ]
//            ],
//            [
//                'name' => 'Double Assassinate',
//                'damage_type' => DamageType::FIXED_TARGET,
//                'attacker_position' => CombatPosition::BACK_LINE,
//                'target_position' => CombatPosition::FRONT_LINE,
//                'target_priority' => TargetPriority::ANY,
//                'tier' => 5,
//                'targets_count' => 2,
//                'item_bases' => [
//                    ItemBase::DAGGER
//                ]
//            ],
//            [
//                'name' => 'Mace Maul',
//                'damage_type' => DamageType::AREA_OF_EFFECT,
//                'attacker_position' => CombatPosition::BACK_LINE,
//                'target_position' => CombatPosition::FRONT_LINE,
//                'target_priority' => TargetPriority::ANY,
//                'tier' => 4,
//                'targets_count' => null,
//                'item_bases' => [
//                    ItemBase::MACE
//                ]
//            ],
//            [
//                'name' => 'Mace Massacre',
//                'damage_type' => DamageType::DISPERSED,
//                'attacker_position' => CombatPosition::BACK_LINE,
//                'target_position' => CombatPosition::FRONT_LINE,
//                'target_priority' => TargetPriority::ANY,
//                'tier' => 5,
//                'targets_count' => null,
//                'item_bases' => [
//                    ItemBase::MACE
//                ]
//            ],
        ];

        $damageTypes = DamageType::all();
        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();
        $itemBases = ItemBase::with('itemTypes')->get();

        foreach($attacks as $attackData) {

            $tier = $attackData['tier'];
            $targetsCount = $attackData['targets_count'];

            /** @var Attack $attack */
            $attack = Attack::query()->create([
                'uuid' => (string) Str::uuid(),
                'name' => $attackData['name'],
                'damage_type_id' => $damageTypes->firstWhere('name', '=', $attackData['damage_type'])->id,
                'attacker_position_id' => $combatPositions->firstWhere('name', '=', $attackData['attacker_position'])->id,
                'target_position_id' => $combatPositions->firstWhere('name', '=', $attackData['target_position'])->id,
                'target_priority_id' => $targetPriorities->firstWhere('name', '=', $attackData['target_priority'])->id,
                'tier' => $tier,
                'targets_count' => $targetsCount
            ]);

            foreach($attackData['item_bases'] as $itemBaseName) {

                /** @var \Illuminate\Support\Collection $itemTypes */
                $itemTypes = $itemBases->where('name', '=', $itemBaseName)->first()->itemTypes;
                $minItemTypeTier = 1;

                if ($tier == 5) {

                    $minItemTypeTier = 5;

                } elseif ($tier === 4) {

                    $minItemTypeTier = 3;

                } elseif ($tier === 3) {

                    if ($targetsCount === 3) {
                        $minItemTypeTier = 6;
                    } else {
                        $minItemTypeTier = 5;
                    }
                } elseif ($tier === 2) {

                    if ($targetsCount === 3) {
                        $minItemTypeTier = 4;
                    } else {
                        $minItemTypeTier = 2;
                    }
                }

                $itemTypes = $itemTypes->reject(function (ItemType $itemType) use ($minItemTypeTier) {
                    return $itemType->tier < $minItemTypeTier;
                });

                $attack->itemTypes()->saveMany($itemTypes);
            }
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
