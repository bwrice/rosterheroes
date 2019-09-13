<?php

use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
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
                'name' => Attack::BASIC_BLADE_ATTACK_NAME,
                'damage_type' => DamageType::SINGLE_TARGET,
                'attacker_position' => CombatPosition::MELEE,
                'target_position' => CombatPosition::MELEE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/12,
                'base_damage_rating' => 5.5,
                'damage_modifier_rating' => 10,
                'item_bases' => [
                    ItemBase::SWORD,
                    ItemBase::DAGGER
                ],
                'resource_costs' => [],
                'requirements' => []
            ],
            [
                'name' => Attack::BASIC_BOW_ATTACK_NAME,
                'damage_type' => DamageType::SINGLE_TARGET,
                'attacker_position' => CombatPosition::MID_RANGE,
                'target_position' => CombatPosition::MELEE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/23,
                'base_damage_rating' => 10,
                'damage_modifier_rating' => 10,
                'item_bases' => [
                    ItemBase::BOW,
                ],
                'resource_costs' => [],
                'requirements' => []
            ],
            [
                'name' => Attack::BASIC_MAGIC_ATTACK_NAME,
                'damage_type' => DamageType::SINGLE_TARGET,
                'attacker_position' => CombatPosition::MID_RANGE,
                'target_position' => CombatPosition::MELEE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/18,
                'base_damage_rating' => 8,
                'damage_modifier_rating' => 10,
                'item_bases' => [
                    ItemBase::STAFF,
                    ItemBase::WAND,
                    ItemBase::ORB
                ],
                'resource_costs' => [],
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
                'damage_modifier_rating' => $attackData['damage_modifier_rating'],
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
