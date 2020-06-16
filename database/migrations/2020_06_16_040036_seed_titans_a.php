<?php

use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Titan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedTitansA extends Migration
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
                'name' => 'Empyrean Golem',
                'level' => 331,
                'enemy_type' => EnemyType::GOLEM,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Bash',
                    'Smash',
                    'Ground Stomp',
                    'Pummel',
                    'Ground Slam'
                ]
            ],
            [
                'name' => 'Lich Baron',
                'level' => 235,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Arrow Release',
                    'Double Arrow Release',
                    'Triple Arrow Release',
                    'Arrow Shot',
                    'Double Arrow Shot',
                    'Triple Arrow Shot',
                    'Arrow Spray',
                    'Arrow Assault'
                ]
            ],
            [
                'name' => 'Lich Overlord',
                'level' => 292,
                'enemy_type' => EnemyType::UNDEAD,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Magic Dart',
                    'Double Magic Dart',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Triple Magic Bullet',
                    'Magic Burst',
                    'Magic Blast',
                    'Blood Swell'
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
