<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/27/19
 * Time: 5:48 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\League;
use Illuminate\Database\Eloquent\Collection;

class LeagueCollection extends Collection
{
    public function live($live = true)
    {
        return $this->filter(function (League $league) use ($live) {
            return $league->isLive() === $live;
        });
    }
}