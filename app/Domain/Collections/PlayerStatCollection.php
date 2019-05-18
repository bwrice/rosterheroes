<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/7/19
 * Time: 8:12 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\PlayerStat;
use Illuminate\Database\Eloquent\Collection;

class PlayerStatCollection extends Collection
{
    public function totalPoints()
    {
        return $this->loadMissing('statType')->sum(function (PlayerStat $playerStat) {
            return $playerStat->totalPoints();
        });
    }

}