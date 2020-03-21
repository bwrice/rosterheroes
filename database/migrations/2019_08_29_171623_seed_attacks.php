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
                'name' => 'Cut',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Cut',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Cut',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Triple Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Blade Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Mace Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Polearm Blitz',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Burst',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Blade Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Mace Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Polearm Torrent',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Assault',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Barrage',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Blast',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Throwing Torrent',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Blade Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Mace Sweep',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Polearm Blitz',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Spray',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Burst',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Blade Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Mace Whirlwind',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Polearm Torrent',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Arrow Assault',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Barrage',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Blast',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Throwing Torrent',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Blade Tornado',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Mace Tornado',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Polearm Onslaught',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Hail of Arrows',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Bolt Bombardment',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Magic Storm',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Throwing Onslaught',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
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
                'uuid' => (string) Str::uuid(),
                'name' => $attackData['name'],
                'damage_type_id' => $damageTypes->firstWhere('name', '=', $attackData['damage_type'])->id,
                'attacker_position_id' => $combatPositions->firstWhere('name', '=', $attackData['attacker_position'])->id,
                'target_position_id' => $combatPositions->firstWhere('name', '=', $attackData['target_position'])->id,
                'target_priority_id' => $targetPriorities->firstWhere('name', '=', $attackData['target_priority'])->id,
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
