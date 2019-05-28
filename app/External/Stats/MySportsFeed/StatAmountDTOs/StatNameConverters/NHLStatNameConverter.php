<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:55 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters;


use App\Domain\Models\StatType;

class NHLStatNameConverter implements StatNameConverter
{

    public function convert(string $msfStatName): string
    {
        switch($msfStatName) {
            case 'goals':
                return StatType::GOAL;
            case 'assists':
                return StatType::HOCKEY_ASSIST;
            case 'hatTricks':
                return StatType::HAT_TRICK;
            case 'shots':
                return StatType::SHOT_ON_GOAL;
            case 'blockedShots':
                return StatType::HOCKEY_BLOCKED_SHOT;
            case 'wins':
                return StatType::GOALIE_WIN;
            case 'saves':
                return StatType::GOALIE_SAVE;
            case 'goalsAgainst':
                return StatType::GOAL_AGAINST;
            default:
                return 'NONE';
        }
    }
}