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
                'config_path' => '/Yaml/Minions/skeleton_scout.yaml',
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
                'config_path' => '/Yaml/Minions/skeleton_guard.yaml',
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
                'config_path' => '/Yaml/Minions/skeleton_archer.yaml',
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
                'config_path' => '/Yaml/Minions/skeleton_mage.yaml',
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
                'config_path' => '/Yaml/Minions/skeleton_solider.yaml',
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
                'config_path' => '/Yaml/Minions/skeleton_marksman.yaml',
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
                'config_path' => $minionData['config_path'],
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
