<?php


namespace App\Domain\Behaviors\TravelTypes;


use App\Domain\Models\Province;

abstract class TravelTypeBehavior
{
    /**
     * @param Province $currentLocation
     * @return Province
     */
    abstract public function getRandomProvinceToTravelTo(Province $currentLocation): Province;
}
