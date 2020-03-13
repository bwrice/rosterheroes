<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
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

        return $playerGameLog->playerStats->sum(function (PlayerStat $playerStat) {
            return $playerStat->statType->getBehavior()->getPointsPer() * $playerStat->amount;
        });
    }
}
