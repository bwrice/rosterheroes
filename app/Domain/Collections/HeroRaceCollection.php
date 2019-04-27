<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/24/19
 * Time: 9:57 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;

class HeroRaceCollection extends Collection
{
    /**
     * @return PositionCollection
     */
    public function positions()
    {
        $positions = new PositionCollection();

        $this->loadMissing('positions')->each(function (HeroRace $heroRace) use (&$positions) {
            $positions = $positions->merge($heroRace->positions);
        });

        return $positions;
    }
}