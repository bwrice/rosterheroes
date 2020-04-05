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
                'name' => 'Skeleton General',
                'level' => 102,
                'enemy_type' => EnemyType::UNDEAD,
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
            [
                'name' => 'Skeleton Overlord',
                'level' => 134,
                'enemy_type' => EnemyType::UNDEAD,
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
                'name' => 'Vampire Elder',
                'level' => 161,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Vampiric Bite',
                    'Triple Magic Dart',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Triple Magic Bullet',
                    'Magic Burst',
                    'Blood Swell'
                ]
            ],
            [
                'name' => 'Vampire Noble',
                'level' => 188,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Vampiric Bite',
                    'Slice',
                    'Double Slice',
                    'Triple Slice',
                    'Cleave',
                    'Double Cleave',
                    'Blade Sweep',
                    'Blood Swell',
                    'Blood Boil'
                ]
            ],
            [
                'name' => 'Vampire Lord',
                'level' => 241,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Vampiric Bite',
                    'Triple Magic Dart',
                    'Double Magic Bullet',
                    'Triple Magic Bullet',
                    'Magic Torpedo',
                    'Double Magic Torpedo',
                    'Triple Magic Torpedo',
                    'Cleave',
                    'Double Cleave',
                    'Magic Burst',
                    'Blood Swell',
                    'Blood Boil'
                ]
            ],
            [
                'name' => 'Werewolf Alpha',
                'level' => 143,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Severe Bite',
                    'Vicious Bite',
                    'Claw',
                    'Double Claw',
                    'Maul'
                ]
            ],
            [
                'name' => 'Oberon Pack Leader',
                'level' => 219,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Fanged Bite',
                    'Severe Bite',
                    'Vicious Bite',
                    'Lethal Bite',
                    'Claw',
                    'Double Claw',
                    'Triple Claw',
                    'Maul'
                ]
            ],
        ]);

        $attacks = Attack::all();

        $titans->each(function ($titanData) use ($attacks) {
            $count = count($titanData['attacks']);
            $attacksToAttach = $attacks->filter(function (Attack $attack) use ($titanData) {
                return in_array($attack->name, $titanData['attacks']);
            });
            if ($count != $attacksToAttach->count()) {
                $missing = collect($titanData['attacks'])->reject(function ($attackName) use ($attacks) {
                    return in_array($attackName, $attacks->pluck('name')->toArray());
                });
                throw new RuntimeException("Not all of the attacks for " . $titanData['name'] . " were found: " . print_r($missing, true));
            }
        });

        $enemyTypes = EnemyType::all();
        $combatPositions = CombatPosition::all();

        $titans->each(function ($titanData) use ($attacks, $enemyTypes, $combatPositions) {

            /** @var Titan $titan */
            $titan = Titan::query()->create([
                'uuid' => Str::uuid(),
                'name' => $titanData['name'],
                'level' => $titanData['level'],
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
