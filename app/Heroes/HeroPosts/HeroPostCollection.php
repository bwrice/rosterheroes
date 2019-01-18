<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/17/19
 * Time: 9:14 PM
 */

namespace App\Heroes\HeroPosts;


use App\HeroRace;
use Illuminate\Database\Eloquent\Collection;

class HeroPostCollection extends Collection
{

    public function heroRace(HeroRace $heroRace)
    {
        return $this->filter(function (HeroPost $heroPost) use ($heroRace) {
            return $heroPost->hero_race_id == $heroRace->id;
        });
    }

    public function postFilled($filled = true)
    {
        return $this->filter(function (HeroPost $heroPost) use ($filled) {
            return $filled ? $heroPost->hero_id : ! $heroPost->hero_id;
        });
    }
}