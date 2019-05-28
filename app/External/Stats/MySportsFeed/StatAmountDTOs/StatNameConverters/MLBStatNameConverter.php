<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:54 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters;


use App\Domain\Models\StatType;

class MLBStatNameConverter implements StatNameConverter
{

    public function convert(string $msfStatName): string
    {
        switch($msfStatName) {
            case 'runs':
                return StatType::RUN_SCORED;
            case 'hits':
                return StatType::HIT;
            case 'secondBaseHits':
                return StatType::DOUBLE;
            case 'thirdBaseHits':
                return StatType::TRIPLE;
            case 'homeruns':
                return StatType::HOME_RUN;
            case 'stolenBases':
                return StatType::STOLEN_BASE;
            case 'runsBattedIn':
                return StatType::RUN_BATTED_IN;
            case 'batterWalks':
                return StatType::BASE_ON_BALLS;
            case 'hitByPitch':
                return StatType::HIT_BY_PITCH;
            case 'wins':
                return StatType::PITCHING_WIN;
            case 'saves':
                return StatType::GOALIE_SAVE;
            case 'inningsPitched':
                return StatType::INNING_PITCHED;
            case 'pitcherStrikeouts':
                return StatType::STRIKEOUT;
            case 'hitsAllowed':
                return StatType::HIT_AGAINST;
            case 'earnedRunsAllowed':
                return StatType::EARNED_RUN_ALLOWED;
            case 'completedGames':
                return StatType::COMPLETE_GAME;
            case 'shutouts':
                return StatType::COMPLETE_GAME_SHUTOUT;
            case 'battersHit':
                return StatType::HIT_BATSMAN;
            case 'pitcherWalks':
                return StatType::BASE_ON_BALLS_AGAINST;
            default:
                return 'NONE';
        }
    }
}