<?php


namespace App\Domain\Behaviors\TravelTypes;


use App\Domain\Models\Province;

class StationaryTravelBehavior extends TravelTypeBehavior
{
    /**
     * @param Province $currentLocation
     * @return Province
     */
    public function getRandomProvinceToTravelTo(Province $currentLocation): Province
    {
        return $currentLocation;
    }
}
