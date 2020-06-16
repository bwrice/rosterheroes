<?php

use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedEnemyAttacks extends Migration
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
                'name' => 'Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
            ],
            [
                'name' => 'Fanged Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
            ],
            [
                'name' => 'Severe Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
            ],
            [
                'name' => 'Vicious Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 4,
                'targets_count' => 1,
            ],
            [
                'name' => 'Vampiric Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 5,
                'targets_count' => 1,
            ],
            [
                'name' => 'Lethal Bite',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 6,
                'targets_count' => 1,
            ],
            [
                'name' => 'Scratch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 1,
            ],
            [
                'name' => 'Double Scratch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 2,
            ],
            [
                'name' => 'Triple Scratch',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 1,
                'targets_count' => 3,
            ],
            [
                'name' => 'Claw',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 1,
            ],
            [
                'name' => 'Double Claw',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 2,
            ],
            [
                'name' => 'Triple Claw',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 2,
                'targets_count' => 3,
            ],
            [
                'name' => 'Pounce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 1,
            ],
            [
                'name' => 'Double Pounce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 2,
            ],
            [
                'name' => 'Triple Pounce',
                'damage_type' => DamageType::FIXED_TARGET,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::BACK_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 3,
                'targets_count' => 3,
            ],
            [
                'name' => 'Maul',
                'damage_type' => DamageType::DISPERSED,
                'attacker_position' => CombatPosition::FRONT_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 5,
                'targets_count' => null,
            ],
            [
                'name' => 'Blood Swell',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 4,
                'targets_count' => null,
            ],
            [
                'name' => 'Blood Boil',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 5,
                'targets_count' => null,
            ],
            [
                'name' => 'Blood Conflagrate',
                'damage_type' => DamageType::AREA_OF_EFFECT,
                'attacker_position' => CombatPosition::BACK_LINE,
                'target_position' => CombatPosition::FRONT_LINE,
                'target_priority' => TargetPriority::ANY,
                'tier' => 6,
                'targets_count' => null,
            ],
        ];

        $damageTypes = DamageType::all();
        $combatPositions = CombatPosition::all();
        $targetPriorities = TargetPriority::all();

        foreach($attacks as $attackData) {

            /** @var Attack $attack */
            Attack::query()->create([
                'uuid' => (string)Str::uuid(),
                'name' => $attackData['name'],
                'damage_type_id' => $damageTypes->firstWhere('name', '=', $attackData['damage_type'])->id,
                'attacker_position_id' => $combatPositions->firstWhere('name', '=', $attackData['attacker_position'])->id,
                'target_position_id' => $combatPositions->firstWhere('name', '=', $attackData['target_position'])->id,
                'target_priority_id' => $targetPriorities->firstWhere('name', '=', $attackData['target_priority'])->id,
                'tier' => $attackData['tier'],
                'targets_count' => $attackData['targets_count']
            ]);
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
