<?php

use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Titan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class SeedTitans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $titans = collect([
            [
                'name' => 'Skeleton Overlord',
                'base_level' => 93,
                'base_damage_rating' => 75,
                'damage_multiplier_rating' => 50,
                'health_rating' => 20,
                'protection_rating' => 5,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Magic Bolt',
                    'Double Magic Bolt',
                    'Triple Magic Bolt',
                    'Magic Burst',
                    'Magic Blast',
                    'Lightning Bolt',
                    'Lightning Strike'
                ]
            ],
            [
                'name' => 'Skeleton General',
                'base_level' => 67,
                'base_damage_rating' => 20,
                'damage_multiplier_rating' => 50,
                'health_rating' => 40,
                'protection_rating' => 40,
                'combat_speed_rating' => 50,
                'block_rating' => 50,
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Triple Slash',
                    'Sword Sweep',
                    'Blade Spin',
                    'Slice',
                    'Double Slice',
                    'Triple Slice',
                ]
            ],
        ]);

        $attacks = Attack::all();

        $titans->each(function ($titanData) use ($attacks) {
            $count = count($titanData['attacks']);
            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($titanData) {
                return in_array($attack->name, $titanData['attacks']);
            });
            if ($count != $attacksToAttach->count() ) {
                throw new RuntimeException("Not all of the attacks for " . $titanData['name'] . " were found");
            }
        });

        $enemyTypes = EnemyType::all();
        $combatPositions = CombatPosition::all();

        $titans->each(function ($titanData) use ($attacks, $enemyTypes, $combatPositions) {

            /** @var Titan $titan */
            $titan = Titan::query()->create([
                'uuid' => Str::uuid(),
                'name' => $titanData['name'],
                'base_level' => $titanData['base_level'],
                'base_damage_rating' => $titanData['base_damage_rating'],
                'damage_multiplier_rating' => $titanData['damage_multiplier_rating'],
                'health_rating' => $titanData['health_rating'],
                'protection_rating' => $titanData['protection_rating'],
                'combat_speed_rating' => $titanData['combat_speed_rating'],
                'block_rating' => $titanData['block_rating'],
                'enemy_type_id' => $enemyTypes->where('name', '=', $titanData['enemy_type'])->first()->id,
                'combat_position_id' => $combatPositions->where('name', '=', $titanData['combat_position'])->first()->id
            ]);

            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($titanData) {
                return in_array($attack->name, $titanData['attacks']);
            });

            $titan->attacks()->saveMany($attacksToAttach);
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
