<?php


namespace App\Domain\Actions;


use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SquadSnapshot;
use App\Facades\CurrentWeek;
use App\HeroSnapshot;
use Illuminate\Support\Str;

class BuildHeroSnapshot
{
    /**
     * @var CalculateHeroFantasyPower
     */
    protected $calculateFantasyPower;
    /**
     * @var CalculateCombatDamage
     */
    protected $calculateCombatDamage;

    public function __construct(CalculateHeroFantasyPower $calculateFantasyPower, CalculateCombatDamage $calculateCombatDamage)
    {
        $this->calculateFantasyPower = $calculateFantasyPower;
        $this->calculateCombatDamage = $calculateCombatDamage;
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

        $hero->getAttacks()->each(function (Attack $attack) use ($heroSnapshot, $fantasyPower) {
            $heroSnapshot->attackSnapshots()->create([
                'uuid' => Str::uuid(),
                'attack_id' => $attack->id,
                'damage' => $this->calculateCombatDamage->execute($attack, $fantasyPower),
                'combat_speed' => $attack->getCombatSpeed(),
                'name' => $attack->name,
                'attacker_position_id' => $attack->attacker_position_id,
                'target_position_id' => $attack->target_priority_id,
                'damage_type_id' => $attack->damage_type_id,
                'target_priority_id' => $attack->target_priority_id,
                'tier' => $attack->tier,
                'targets_count' => $attack->targets_count
            ]);
        });

        $items = $hero->items;
        $heroSnapshot->items()->saveMany($items);

        return $heroSnapshot->fresh();
    }
}
