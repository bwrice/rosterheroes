<?php


namespace App\Domain\Actions;

use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Measurable;
use App\Domain\Models\SquadSnapshot;
use App\Facades\CurrentWeek;
use App\Domain\Models\HeroSnapshot;
use Illuminate\Support\Str;

class BuildHeroSnapshot
{
    /**
     * @var CalculateHeroFantasyPower
     */
    protected $calculateFantasyPower;
    /**
     * @var BuildItemSnapshot
     */
    protected $buildItemSnapshot;

    public function __construct(CalculateHeroFantasyPower $calculateFantasyPower, BuildItemSnapshot $buildItemSnapshot)
    {
        $this->calculateFantasyPower = $calculateFantasyPower;
        $this->buildItemSnapshot = $buildItemSnapshot;
    }


    public const EXCEPTION_CODE_SNAPSHOT_WEEK_NOT_CURRENT = 1;
    public const EXCEPTION_CODE_WEEK_NOT_FINALIZING = 2;
    public const EXCEPTION_CODE_SNAPSHOT_MISMATCH = 3;

    public function execute(SquadSnapshot $squadSnapshot, Hero $hero): HeroSnapshot
    {
        if ($squadSnapshot->week_id !== CurrentWeek::id()) {
            throw new \Exception("Squad snapshot does not match current week", self::EXCEPTION_CODE_SNAPSHOT_WEEK_NOT_CURRENT);
        }

        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Current Week not finalizing", self::EXCEPTION_CODE_WEEK_NOT_FINALIZING);
        }

        if ($squadSnapshot->squad_id !== $hero->squad_id) {
            throw new \Exception("Squad snapshot and Hero have mismatched squads", self::EXCEPTION_CODE_SNAPSHOT_MISMATCH);
        }

        $fantasyPower = round($this->calculateFantasyPower->execute($hero), 2);

        /** @var HeroSnapshot $heroSnapshot */
        $heroSnapshot = HeroSnapshot::query()->create([
            'uuid' => Str::uuid(),
            'squad_snapshot_id' => $squadSnapshot->id,
            'hero_id' => $hero->id,
            'player_spirit_id' => $hero->player_spirit_id,
            'combat_position_id' => $hero->combat_position_id,
            'protection' => round($hero->getProtection(), 2),
            'block_chance' => round($hero->getBlockChance(), 2),
            'fantasy_power' => $fantasyPower
        ]);

        $hero->measurables->each(function (Measurable $measurable) use ($heroSnapshot) {
            $heroSnapshot->measurableSnapshots()->create([
                'uuid' => Str::uuid(),
                'measurable_id' => $measurable->id,
                'pre_buffed_amount' => $measurable->getPreBuffedAmount(),
                'buffed_amount' => $measurable->getBuffedAmount(),
                'final_amount' => $measurable->getCurrentAmount()
            ]);
        });

        $hero->items->each(function (Item $item) use ($heroSnapshot) {
            $this->buildItemSnapshot->execute($item, $heroSnapshot);
        });

        // Save spells to snapshot
        $heroSnapshot->spells()->saveMany($hero->spells);

        return $heroSnapshot->fresh();
    }
}
