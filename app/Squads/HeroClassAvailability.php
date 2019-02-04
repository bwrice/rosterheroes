<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 2/3/19
 * Time: 1:37 PM
 */

namespace App\Squads;


use App\HeroClass;
use App\Squad;

class HeroClassAvailability
{
    public function get(Squad $squad)
    {
        if ($squad->inCreationState()) {

            $emptyHeroPosts = $squad->heroPosts->postFilled(false);
            $requiredClasses = HeroClass::requiredStarting()->get();
            $heroes = $squad->getHeroes();

            $missingClasses = $requiredClasses->filter(function (HeroClass $heroClass) use ($heroes) {
                $heroWithClass = $heroes->filterByClass($heroClass)->first();
                return $heroWithClass == null;
            });

            if ($missingClasses->count() >= $emptyHeroPosts->count()) {
                return $missingClasses;
            }
        }

        return HeroClass::all();
    }
}