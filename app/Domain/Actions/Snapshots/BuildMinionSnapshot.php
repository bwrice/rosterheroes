<?php


namespace App\Domain\Actions\Snapshots;


use App\Domain\Actions\BuildAttackSnapshot;
use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Illuminate\Support\Str;

class BuildMinionSnapshot
{
    public const EXCEPTION_CODE_INVALID_WEEK = 1;
    public const EXCEPTION_CODE_WEEK_NOT_FINALIZING = 2;

    /**
     * @var CalculateFantasyPower
     */
    protected $calculateFantasyPower;
    /**
     * @var BuildAttackSnapshot
     */
    protected $buildAttackSnapshot;

    public function __construct(CalculateFantasyPower $calculateFantasyPower, BuildAttackSnapshot $buildAttackSnapshot)
    {
        $this->calculateFantasyPower = $calculateFantasyPower;
        $this->buildAttackSnapshot = $buildAttackSnapshot;
    }

    public function execute(Minion $minion, Week $week)
    {
        if (CurrentWeek::id() !== $week->id) {
            throw new \Exception("Cannot build minion snapshot from non-current week", self::EXCEPTION_CODE_INVALID_WEEK);
        }

        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Cannot build minion snapshot when week is not finalizing", self::EXCEPTION_CODE_WEEK_NOT_FINALIZING);
        }

        $fantasyPower = $this->calculateFantasyPower->execute($minion->getFantasyPoints());

        /** @var MinionSnapshot $minionSnapshot */
        $minionSnapshot = $minion->minionSnapshots()->create([
            'uuid' => Str::uuid(),
            'week_id' => $week->id,
            'combat_position_id' => $minion->combat_position_id,
            'enemy_type_id' => $minion->enemy_type_id,
            'level' => $minion->level,
            'starting_health' => $minion->getStartingHealth(),
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
    }
}
