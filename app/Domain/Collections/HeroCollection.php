<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/13/19
 * Time: 12:35 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Facades\HeroService;
use Illuminate\Database\Eloquent\Collection;

class HeroCollection extends Collection
{
    /**
     * @return int
     */
    public function totalEssenceCost()
    {
        return $this->loadMissing('playerSpirit')->sum(function(Hero $hero) {
            return $hero->essenceUsed();
        });
    }

    public function filterByClass(HeroClass $heroClass)
    {
        return $this->filter(function (Hero $hero) use ($heroClass) {
            return $hero->hero_class_id === $heroClass->id;
        });
    }

    public function combatReady()
    {
        return $this->filter(function (Hero $hero) {
            return HeroService::combatReady($hero);
        });
    }
}
