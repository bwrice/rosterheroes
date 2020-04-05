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
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Slash',
                    'Arrow Release',
                ]
            ],
            [
                'name' => 'Skeleton Guard',
                'level' => 13,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Slash',
                    'Double Slash',
                ]
            ],
            [
                'name' => 'Skeleton Archer',
                'level' => 17,
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
                'level' => 18,
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
                'level' => 21,
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
                'level' => 23,
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
                'name' => 'Skeleton Battler',
                'level' => 31,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Pierce',
                    'Double Pierce',
                    'Stab',
                    'Double Stab',
                    'Triple Stab',
                    'Polearm Strike',
                ]
            ],
            [
                'name' => 'Skeleton Captain',
                'level' => 40,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Smash',
                    'Double Smash',
                    'Triple Smash',
                    'Clobber',
                    'Double Clobber',
                    'Mace Sweep',
                ]
            ],
            [
                'name' => 'Young Werewolf',
                'level' => 15,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ]
            ],
            [
                'name' => 'Werewolf',
                'level' => 21,
                'enemy_type' => EnemyType::WEREWOLF,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bite',
                    'Scratch',
                    'Double Scratch'
                ]
            ],
            [
                'name' => 'Werewolf Thrasher',
                'level' => 28,
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
                'name' => 'Werewolf Mangler',
                'level' => 37,
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
                'level' => 45,
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
                'name' => 'Werewolf Mauler',
                'level' => 51,
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
                'name' => 'Werewolf Maimer',
                'level' => 59,
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
                'name' => 'Werewolf Eviscerator',
                'level' => 90,
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
                'level' => $minionData['level'],
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
