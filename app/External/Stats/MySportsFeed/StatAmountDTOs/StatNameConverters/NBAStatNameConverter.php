<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:03 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters;


use App\Domain\Models\StatType;

class NBAStatNameConverter implements StatNameConverter
{

    public function convert(string $msfStatName): string
    {
        switch ($msfStatName) {
            case 'fg3PtMade':
                return StatType::THREE_POINTER;
            case 'reb':
                return StatType::REBOUND;
            case 'ast':
                return StatType::BASKETBALL_ASSIST;
            case 'pts':
                return StatType::POINT_MADE;
            case 'tov':
                return StatType::TURNOVER;
            case 'stl':
                return StatType::STEAL;
            case 'blk':
                return StatType::BASKETBALL_BLOCK;
            default:
                return 'NONE';
        }
    }
}