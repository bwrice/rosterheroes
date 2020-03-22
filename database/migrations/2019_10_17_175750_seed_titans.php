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
                'config_path' => '/Yaml/Titans/skeleton_overlord.yaml',
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Double Slash',
                    'Triple Slash',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Triple Magic Dart',
                    'Triple Magic Bullet',
                    'Magic Burst',
                    'Magic Blast',
                ]
            ],
            [
                'name' => 'Skeleton General',
                'config_path' => '/Yaml/Titans/skeleton_general.yaml',
                'enemy_type' => EnemyType::SKELETON,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Arrow Release',
                    'Slice',
                    'Double Slice',
                    'Triple Slice',
                    'Blade Sweep',
                    'Blade Whirlwind',
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
                'config_path' => $titanData['config_path'],
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
