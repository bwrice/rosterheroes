<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/21/19
 * Time: 10:30 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters;


use App\Domain\Models\StatType;

class NFLStatNameConverter
{
    public function convert(string $msfStatName)
    {
        switch($msfStatName) {
            case 'passYards':
                return StatType::PASS_YARD;
            case 'passTD':
                return StatType::PASS_TD;
            case 'passInt':
                return StatType::INTERCEPTION;
            case 'rushYards':
                return StatType::RUSH_YARD;
            case 'rushTD':
                return StatType::RUSH_TD;
            case 'recYards':
                return StatType::REC_YARD;
            case 'recTD':
                return StatType::REC_TD;
            case 'receptions':
                return StatType::RECEPTION;
            case 'fumLost':
                return StatType::FUMBLE_LOST;
            default:
                return 'NONE';
        }
    }
}