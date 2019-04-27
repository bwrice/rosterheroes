<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/17/19
 * Time: 9:14 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;

class HeroPostCollection extends Collection
{

    /**
     * @param HeroRace $heroRace
     * @return HeroPostCollection
     */
    public function heroRace(HeroRace $heroRace)
    {
        return $this->loadMissing('heroPostType.heroRaces')->filter(function (HeroPost $heroPost) use ($heroRace) {
            return in_array($heroRace->id, $heroPost->heroPostType->heroRaces->pluck('id')->toArray());
        });
    }

    /**
     * @param bool $filled
     * @return HeroPostCollection
     */
    public function postFilled($filled = true)
    {
        return $this->filter(function (HeroPost $heroPost) use ($filled) {
            return $filled ? $heroPost->hero_id : ! $heroPost->hero_id;
        });
    }

    public function heroRaces()
    {
        $heroRaces = new HeroRaceCollection();

        $this->loadMissing('heroPostType.heroRaces')->each(function (HeroPost $heroPost) use ($heroRaces) {
            $heroRaces->merge($heroPost->heroPostType->heroRaces);
        });

        return $heroRaces;
    }
}