<?php

use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
use App\Domain\Models\TargetPriority;
use App\Domain\Models\TargetRange;
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
                'target_range' => TargetRange::MELEE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/12,
                'damage_rating' => 5.5,
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
                'target_range' => TargetRange::MID_RANGE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/23,
                'damage_rating' => 10,
                'item_bases' => [
                    ItemBase::BOW,
                ],
                'resource_costs' => [],
                'requirements' => []
            ],
            [
                'name' => Attack::BASIC_MAGIC_ATTACK_NAME,
                'damage_type' => DamageType::SINGLE_TARGET,
                'target_range' => TargetRange::MID_RANGE,
                'target_priority' => TargetPriority::ANY,
                'grade' => 5,
                'speed_rating' => 100/18,
                'damage_rating' => 8,
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
        $targetRanges = TargetRange::all();
        $targetPriorities = TargetPriority::all();
        $itemBases = ItemBase::all();

        foreach($attacks as $attackData) {
            /** @var Attack $attack */
            $attack = Attack::query()->create([
                'name' => $attackData['name'],
                'damage_type_id' => $damageTypes->firstWhere('name', '=', $attackData['damage_type'])->id,
                'target_range_id' => $targetRanges->firstWhere('name', '=', $attackData['target_range'])->id,
                'target_priority_id' => $targetPriorities->firstWhere('name', '=', $attackData['target_priority'])->id,
                'grade' => $attackData['grade'],
                'speed_rating' => $attackData['speed_rating'],
                'damage_rating' => $attackData['damage_rating'],
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
