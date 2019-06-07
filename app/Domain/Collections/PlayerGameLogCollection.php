<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/16/19
 * Time: 10:10 PM
 */

namespace App\Domain\Collections;


use App\Domain\Interfaces\ConvertsToWeightedValues;
use App\Domain\Math\WeightedValue;
use App\Domain\Models\PlayerGameLog;
use Illuminate\Database\Eloquent\Collection;

class PlayerGameLogCollection extends Collection implements ConvertsToWeightedValues
{

    public function toWeightedValues(): WeightedValueCollection
    {
        /*
         * Sort by game-time, older games being first, and reset the indexes
         * so the oldest game starts with a 0 index and they increase with recency
         */
        $sorted = $this->sortByGameTime()->values();

        $weightedCollection = $sorted->loadMissing('playerStats')->map(function (PlayerGameLog $playerGameLog, $index) {
            $totalPoints = $playerGameLog->playerStats->totalPoints();
            // Games with absolute value of total points less than 1 will be ignored
            if (abs($totalPoints) < 1) {
                return new WeightedValue(0, $totalPoints);
            }
            // The higher the index, ie more recent the game, the larger the weight
            $weight = 1.2 ** $index;
            return new WeightedValue($weight, $totalPoints);
        });

        return new WeightedValueCollection($weightedCollection->values());
    }

    public function sortByGameTime($descending = false)
    {
        return $this->loadMissing('game')->sortBy(function (PlayerGameLog $playerGameLog) {
            return $playerGameLog->game->starts_at->timestamp;
        }, SORT_REGULAR, $descending);
    }

}