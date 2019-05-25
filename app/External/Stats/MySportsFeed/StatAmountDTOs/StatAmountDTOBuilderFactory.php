<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/23/19
 * Time: 9:50 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;


use App\Domain\Models\League;
use App\Exceptions\UnknownLeagueException;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\MLBStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NBAStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NFLStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NHLStatNameConverter;

class StatAmountDTOBuilderFactory
{
    /**
     * @param League $league
     * @return StatAmountDTOBuilder
     */
    public function getStatAmountDTOBuilder(League $league): StatAmountDTOBuilder
    {
        switch($league->abbreviation) {
            case League::NFL:
                return new StatAmountDTOBuilder(new NFLStatNameConverter(), [
                    'passing',
                    'rushing',
                    'receiving',
                    'fumbles'
                ]);
            case League::NBA:
                return new StatAmountDTOBuilder(new NBAStatNameConverter(), [
                    'fieldGoals',
                    'rebounds',
                    'offense',
                    'defense'
                ]);
            case League::MLB:
                return new StatAmountDTOBuilder(new MLBStatNameConverter(), [
                    'batting',
                    'pitching'
                ]);
            case League::NHL:
                return new StatAmountDTOBuilder(new NHLStatNameConverter(), [
                    'scoring',
                    'skating',
                    'goaltending'
                ]);
        }
        throw new UnknownLeagueException($league);
    }
}