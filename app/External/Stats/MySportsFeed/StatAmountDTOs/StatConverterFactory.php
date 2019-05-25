<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:37 PM
 */

namespace App\External\Stats\MySportsFeed\StatAmountDTOs;


use App\Domain\Models\League;

class StatConverterFactory
{
    /**
     * @param League $league
     * @return StatAmountDTOBuilderInterface
     */
    public function getStatConverter(League $league): StatAmountDTOBuilderInterface
    {
        switch($league->abbreviation) {
            case League::NFL:
                return app(NFLStatAmountDTOBuilder::class);
            case League::MLB:
                return app(MLBStatAmountDTOBuilder::class);
            case League::NBA:
                return app(NBAStatAmountDTOBuilder::class);
            case League::NHL:
                return app(NHLStatAmountDTOBuilder::class);
        }
        throw new \InvalidArgumentException("Unknown League: " . $league->abbreviation);
    }
}