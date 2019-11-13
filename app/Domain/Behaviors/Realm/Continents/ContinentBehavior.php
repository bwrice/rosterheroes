<?php


namespace App\Domain\Behaviors\Realm\Continents;


use App\Domain\Behaviors\Realm\MapBehavior;

abstract class ContinentBehavior extends MapBehavior
{
    protected $minLevelRequirement = 0;

    /**
     * @return int
     */
    public function getMinLevelRequirement(): int
    {
        return $this->minLevelRequirement;
    }
}
