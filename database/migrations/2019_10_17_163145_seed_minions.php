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
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Slash',
                    'Arrow Release',
                ]
            ],
            [
                'name' => 'Skeleton Guard',
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                ]
            ],
            [
                'name' => 'Skeleton Archer',
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Arrow Spray',
                ]
            ],
            [
                'name' => 'Skeleton Mage',
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Magic Dart',
                    'Double Magic Dart',
                    'Magic Burst'
                ]
            ],
            [
                'name' => 'Skeleton Soldier',
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                    'Triple Slash',
                    'Blade Sweep',
                ]
            ],
            [
                'name' => 'Skeleton Marksman',
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Triple Arrow Release',
                    'Arrow Spray',
                ]
            ],
            [
                'name' => 'Werewolf Pup',
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch'
                ]
            ],
            [
                'name' => 'Young Werewolf',
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ]
            ],
            [
                'name' => 'Werewolf Mauler',
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
                ]
            ],
            [
                'name' => 'Werewolf Ravager',
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Fanged Bite',
                    'Scratch',
                    'Double Scratch'
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

                $missing = collect($minionData['attacks'])->filter(function ($attackName) use ($attacksToAttach) {
                    $match = $attacksToAttach->first(function (Attack $attack) use ($attackName) {
                        return $attack->name === $attackName;
                    });
                    return is_null($match);
                })->first();
                throw new RuntimeException("Not all of the attacks for " . $minionData['name'] . " were found: " . $missing);
            }
        });

        $enemyTypes = EnemyType::all();
        $combatPositions = CombatPosition::all();

        $minions->each(function ($minionData) use ($enemyTypes, $combatPositions, $attacks) {

            $name = $minionData['name'];

            /** @var Minion $minion */
            $minion = Minion::query()->create([
                'uuid' => Str::uuid(),
                'name' => $minionData['name'],
                'config_path' => '/Yaml/Minions/' . str_replace(strtolower($name), '' , '_') . '.yaml',
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
