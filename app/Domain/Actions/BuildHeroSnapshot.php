<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SquadSnapshot;
use App\Facades\CurrentWeek;
use App\HeroSnapshot;
use Illuminate\Support\Str;

class BuildHeroSnapshot
{
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

        /** @var HeroSnapshot $heroSnapshot */
        $heroSnapshot = HeroSnapshot::query()->create([
            'uuid' => Str::uuid(),
            'squad_snapshot_id' => $squadSnapshot->id,
            'hero_id' => $hero->id,
            'player_spirit_id' => $hero->player_spirit_id,
            'combat_position_id' => $hero->combat_position_id,
            'health' => $hero->getCurrentMeasurableAmount(MeasurableType::HEALTH),
            'stamina' => $hero->getCurrentMeasurableAmount(MeasurableType::STAMINA),
            'mana' => $hero->getCurrentMeasurableAmount(MeasurableType::MANA),
            'protection' => $hero->getProtection(),
            'block_chance' => $hero->getBlockChance()
        ]);

        return $heroSnapshot;
    }
}
