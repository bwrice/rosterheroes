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
                'damage_rating' => 35,
                'health_rating' => 35,
                'protection_rating' => 30,
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
                'damage_rating' => 15,
                'health_rating' => 35,
                'protection_rating' => 50,
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
                'damage_rating' => 60,
                'health_rating' => 15,
                'protection_rating' => 25,
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
                'damage_rating' => 75,
                'health_rating' => 20,
                'protection_rating' => 5,
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
                'damage_rating' => 20,
                'health_rating' => 40,
                'protection_rating' => 40,
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
        ]);

        $attacks = Attack::all();

        $minions->each(function ($minionData) {
            $ratingSum = $minionData['damage_rating'] + $minionData['health_rating'] + $minionData['protection_rating'];
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
                'damage_rating' => $minionData['damage_rating'],
                'health_rating' => $minionData['health_rating'],
                'protection_rating' => $minionData['protection_rating'],
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
