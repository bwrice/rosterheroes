<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\StatType;
use App\Exceptions\CalculateHeroFantasyPowerException;

class CalculateHeroFantasyPower
{
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
            $fantasyPoints = $playerStat->statType->getBehavior()->getTotalPoints($playerStat->amount);
            return $fantasyPoints * $matchingMeasurable->getBuffedAmount()/100;
        });

        return max(0, $this->getFantasyPower($fantasyPoints));
    }

    protected function getFantasyPower(float $totalPoints)
    {
        $fantasyPower = 0;
        $coefficient = 1;
        $remaining = $totalPoints;
        while ($remaining > 0) {
            $pointsToMultiply = $remaining < 10 ? $remaining : 10;
            $fantasyPower += ($coefficient * $pointsToMultiply);
            $remaining -= 10;
            $coefficient *= .8;
        }
        return $fantasyPower;
    }
}
