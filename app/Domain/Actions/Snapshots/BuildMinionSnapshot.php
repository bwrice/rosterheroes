<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuildMinionSnapshot extends BuildSnapshot
{
    protected CalculateFantasyPower $calculateFantasyPower;
    protected BuildAttackSnapshot $buildAttackSnapshot;

    public function __construct(CalculateFantasyPower $calculateFantasyPower, BuildAttackSnapshot $buildAttackSnapshot)
    {
        $this->calculateFantasyPower = $calculateFantasyPower;
        $this->buildAttackSnapshot = $buildAttackSnapshot;
    }

    public function handle(Minion $minion)
    {
        $fantasyPower = $this->calculateFantasyPower->execute($minion->getFantasyPoints());

        return DB::transaction(function () use ($minion, $fantasyPower) {

            /** @var MinionSnapshot $minionSnapshot */
            $minionSnapshot = $minion->minionSnapshots()->create([
                'uuid' => Str::uuid(),
                'week_id' => CurrentWeek::id(),
                'combat_position_id' => $minion->combat_position_id,
                'enemy_type_id' => $minion->enemy_type_id,
                'name' => $minion->name,
                'level' => $minion->level,
                'starting_health' => $minion->getStartingHealth(),
                'starting_stamina' => $minion->getStartingStamina(),
                'starting_mana' => $minion->getStartingMana(),
                'protection' => $minion->getProtection(),
                'block_chance' => $minion->getBlockChance(),
                'fantasy_power' => $fantasyPower,
                'experience_reward' => $minion->getExperienceReward(),
                'favor_reward' => $minion->getFavorReward()
            ]);

            $minion->attacks->each(function (Attack $attack) use ($minionSnapshot, $fantasyPower) {
                $this->buildAttackSnapshot->execute($attack, $minionSnapshot, $fantasyPower);
            });

            $minion->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($minionSnapshot) {
                $minionSnapshot->chestBlueprints()->save($chestBlueprint, [
                    'chance' => $chestBlueprint->pivot->chance,
                    'count' => $chestBlueprint->pivot->count
                ]);
            });

            return $minionSnapshot;
        });
    }
}
