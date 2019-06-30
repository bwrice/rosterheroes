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
use Illuminate\Database\Eloquent\Collection;

class HeroCollection extends Collection
{
    /**
     * @return int
     */
    public function totalEssenceCost()
    {
        // TODO get essence from player spirit
        return $this->sum(function(Hero $hero) {
            return $hero->essence ?: 0;
        });
    }

    public function filterByClass(HeroClass $heroClass)
    {
        return $this->filter(function (Hero $hero) use ($heroClass) {
            return $hero->hero_class_id === $heroClass->id;
        });
    }
}