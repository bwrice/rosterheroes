<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\PlayerStat;
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
        if (! $playerGameLog) {
            throw new CalculateHeroFantasyPowerException($hero, "No player game log for player spirit", CalculateHeroFantasyPowerException::CODE_NO_PLAYER_GAME_LOG);
        }

        $heroMeasurables = $hero->measurables;

        $fantasyPoints = $playerGameLog->playerStats->sum(function (PlayerStat $playerStat) use ($heroMeasurables) {
            /** @var Measurable $matchingMeasurable */
            $matchingMeasurable = $heroMeasurables->first(function (Measurable $measurable) use ($playerStat) {
                $statTypeNames = $measurable->measurableType->getBehavior()->getStatTypeNames();
                return in_array($playerStat->statType->name, $statTypeNames);
            });
            $fantasyPoints = $playerStat->statType->getBehavior()->getPointsPer() * $playerStat->amount;
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