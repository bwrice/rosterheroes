<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\StatType;
use App\Exceptions\CalculateHeroFantasyPowerException;

class CalculateHeroFantasyPower
{
    /**
     * @var CalculateHeroFantasyPower
     */
    protected $calculateFantasyPower;

    public function __construct(CalculateHeroFantasyPower $calculateFantasyPower)
    {
        $this->calculateFantasyPower = $calculateFantasyPower;
    }


    public function execute(Hero $hero)
    {
        $playerSpirit = $hero->playerSpirit;
        if (! $playerSpirit) {
            throw new CalculateHeroFantasyPowerException($hero, "No player spirit for hero", CalculateHeroFantasyPowerException::CODE_NO_PLAYER_SPIRIT);
        }

        $playerGameLog = $playerSpirit->playerGameLog;
        $heroMeasurables = $hero->measurables;

        $fantasyPoints = $playerGameLog->playerStats->sum(function (PlayerStat $playerStat) use ($heroMeasurables) {
            /** @var Measurable $matchingMeasurable */
            $matchingMeasurable = $heroMeasurables->first(function (Measurable $measurable) use ($playerStat) {
                $statTypeNames = $measurable->measurableType->statTypes->map(function (StatType $statType) {
                    return $statType->name;
                })->toArray();
                return in_array($playerStat->statType->name, $statTypeNames);
            });
            return $playerStat->getFantasyPoints() * $matchingMeasurable->getFantasyPointsModifier();
        });

        return max(0, $this->calculateFantasyPower->execute($fantasyPoints));
    }
}
