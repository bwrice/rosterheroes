<?php

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
                'base_damage_rating' => 35,
                'damage_multiplier_rating' => 50,
                'health_rating' => 35,
                'protection_rating' => 30,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Slash',
                    'Arrow',
                    'Arrow Spray'
                ]
            ],
            [
                'name' => 'Skeleton Guard',
                'level' => 13,
                'base_damage_rating' => 15,
                'damage_multiplier_rating' => 50,
                'health_rating' => 35,
                'protection_rating' => 50,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                    'Sword Sweep',
                ]
            ],
            [
                'name' => 'Skeleton Archer',
                'level' => 15,
                'base_damage_rating' => 60,
                'damage_multiplier_rating' => 50,
                'health_rating' => 15,
                'protection_rating' => 25,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Arrow',
                    'Double Arrow',
                    'Arrow Spray',
                    'Deep Shot'
                ]
            ],
            [
                'name' => 'Skeleton Mage',
                'level' => 17,
                'base_damage_rating' => 75,
                'damage_multiplier_rating' => 50,
                'health_rating' => 20,
                'protection_rating' => 5,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Magic Bolt',
                    'Double Magic Bolt',
                    'Triple Magic Bolt',
                    'Magic Burst',
                    'Magic Blast'
                ]
            ],
            [
                'name' => 'Skeleton Soldier',
                'level' => 21,
                'base_damage_rating' => 20,
                'damage_multiplier_rating' => 50,
                'health_rating' => 40,
                'protection_rating' => 40,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                    'Triple Slash',
                    'Slice',
                    'Axe Sweep',
                    'Blade Spin'
                ]
            ],
            [
                'name' => 'Skeleton Marksman',
                'level' => 23,
                'base_damage_rating' => 70,
                'damage_multiplier_rating' => 50,
                'health_rating' => 25,
                'protection_rating' => 5,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow',
                    'Double Arrow',
                    'Arrow Spray',
                    'Long Shot',
                    'Double Long Shot'
                ]
            ],
        ]);

        $attacks = Attack::all();

        $minions->each(function ($minionData) {
            //TODO update based off new rating values
            $ratingSum = $minionData['base_damage_rating'] + $minionData['health_rating'] + $minionData['protection_rating'];
            if ($ratingSum != 100 ) {
                throw new RuntimeException("Rating sum of: " . $ratingSum . " does not equal 100");
            }
        });

        $minions->each(function ($minionData) use ($attacks) {
            $count = count($minionData['attacks']);
            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($minionData) {
                return in_array($attack->name, $minionData['attacks']);
            });
            if ($count != $attacksToAttach->count() ) {
                throw new RuntimeException("Not all of the attacks for " . $minionData['name'] . " were found");
            }
        });

        $enemyTypes = EnemyType::all();
        $combatPositions = CombatPosition::all();

        $minions->each(function ($minionData) use ($enemyTypes, $combatPositions, $attacks) {

            /** @var Minion $minion */
            $minion = Minion::query()->create([
                'uuid' => Str::uuid(),
                'name' => $minionData['name'],
                'level' => $minionData['level'],
                'base_damage_rating' => $minionData['base_damage_rating'],
                'damage_multiplier_rating' => $minionData['damage_multiplier_rating'],
                'health_rating' => $minionData['health_rating'],
                'protection_rating' => $minionData['protection_rating'],
                'combat_speed_rating' => $minionData['combat_speed_rating'],
                'block_rating' => $minionData['block_rating'],
                'enemy_type_id' => $enemyTypes->where('name', '=', $minionData['enemy_type'])->first()->id,
                'combat_position_id' => $combatPositions->where('name', '=', $minionData['combat_position'])->first()->id
            ]);
            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($minionData) {
                return in_array($attack->name, $minionData['attacks']);
            });

            $minion->attacks()->saveMany($attacksToAttach);
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
