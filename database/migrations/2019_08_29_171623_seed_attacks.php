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
                'config_path' => '/Yaml/Attacks/cut.yaml'
            ],
            [
                'name' => 'Double Cut',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_cut.yaml'
            ],
            [
                'name' => 'Triple Cut',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_cut.yaml'
            ],
            [
                'name' => 'Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/slash.yaml'
            ],
            [
                'name' => 'Double Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_slash.yaml'
            ],
            [
                'name' => 'Triple Slash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_slash.yaml'
            ],
            [
                'name' => 'Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/poke.yaml'
            ],
            [
                'name' => 'Double Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_poke.yaml'
            ],
            [
                'name' => 'Triple Poke',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_poke.yaml'
            ],
            [
                'name' => 'Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/whack.yaml'
            ],
            [
                'name' => 'Double Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_whack.yaml'
            ],
            [
                'name' => 'Triple Whack',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_whack.yaml'
            ],
            [
                'name' => 'Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/arrow_release.yaml'
            ],
            [
                'name' => 'Double Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_arrow_release.yaml'
            ],
            [
                'name' => 'Triple Arrow Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_arrow_release.yaml'
            ],
            [
                'name' => 'Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/bolt_release.yaml'
            ],
            [
                'name' => 'Double Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_bolt_release.yaml'
            ],
            [
                'name' => 'Triple Bolt Release',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_bolt_release.yaml'
            ],
            [
                'name' => 'Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_dart.yaml'
            ],
            [
                'name' => 'Double Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_magic_dart.yaml'
            ],
            [
                'name' => 'Triple Magic Dart',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_magic_dart.yaml'
            ],
            [
                'name' => 'Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/chuck.yaml'
            ],
            [
                'name' => 'Double Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_chuck.yaml'
            ],
            [
                'name' => 'Triple Chuck',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_chuck.yaml'
            ],
            [
                'name' => 'Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/stab.yaml'
            ],
            [
                'name' => 'Double Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_stab.yaml'
            ],
            [
                'name' => 'Triple Stab',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_stab.yaml'
            ],
            [
                'name' => 'Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/slice.yaml'
            ],
            [
                'name' => 'Double Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_slice.yaml'
            ],
            [
                'name' => 'Triple Slice',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_slice.yaml'
            ],
            [
                'name' => 'Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/slice.yaml'
            ],
            [
                'name' => 'Double Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_slice.yaml'
            ],
            [
                'name' => 'Triple Smash',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_slice.yaml'
            ],
            [
                'name' => 'Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/arrow_shot.yaml'
            ],
            [
                'name' => 'Double Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_arrow_shot.yaml'
            ],
            [
                'name' => 'Triple Arrow Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_arrow_shot.yaml'
            ],
            [
                'name' => 'Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/bolt_shot.yaml'
            ],
            [
                'name' => 'Double Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_bolt_shot.yaml'
            ],
            [
                'name' => 'Triple Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_bolt_shot.yaml'
            ],
            [
                'name' => 'Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/bolt_shot.yaml'
            ],
            [
                'name' => 'Double Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_bolt_shot.yaml'
            ],
            [
                'name' => 'Triple Bolt Shot',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_bolt_shot.yaml'
            ],
            [
                'name' => 'Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_bullet.yaml'
            ],
            [
                'name' => 'Double Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_magic_bullet.yaml'
            ],
            [
                'name' => 'Triple Magic Bullet',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_magic_bullet.yaml'
            ],
            [
                'name' => 'Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/hurl.yaml'
            ],
            [
                'name' => 'Double Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_hurl.yaml'
            ],
            [
                'name' => 'Triple Hurl',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_hurl.yaml'
            ],
            [
                'name' => 'Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/puncture.yaml'
            ],
            [
                'name' => 'Double Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_puncture.yaml'
            ],
            [
                'name' => 'Triple Puncture',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_puncture.yaml'
            ],
            [
                'name' => 'Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/cleave.yaml'
            ],
            [
                'name' => 'Double Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_cleave.yaml'
            ],
            [
                'name' => 'Triple Cleave',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_cleave.yaml'
            ],
            [
                'name' => 'Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/clobber.yaml'
            ],
            [
                'name' => 'Double Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_clobber.yaml'
            ],
            [
                'name' => 'Triple Clobber',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_clobber.yaml'
            ],
            [
                'name' => 'Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/arrow_missile.yaml'
            ],
            [
                'name' => 'Double Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_arrow_missile.yaml'
            ],
            [
                'name' => 'Triple Arrow Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_arrow_missile.yaml'
            ],
            [
                'name' => 'Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/bolt_shot.yaml'
            ],
            [
                'name' => 'Double Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_bolt_shot.yaml'
            ],
            [
                'name' => 'Triple Bolt Missile',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::HIGH_GROUND,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_bolt_shot.yaml'
            ],
            [
                'name' => 'Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/magic_bullet.yaml'
            ],
            [
                'name' => 'Double Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_magic_bullet.yaml'
            ],
            [
                'name' => 'Triple Magic Torpedo',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_magic_bullet.yaml'
            ],
            [
                'name' => 'Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/hurl.yaml'
            ],
            [
                'name' => 'Double Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/double_hurl.yaml'
            ],
            [
                'name' => 'Triple Launch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'config_path' => '/Yaml/Attacks/triple_hurl.yaml'
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
                'uuid' => (string) Str::uuid(),
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
