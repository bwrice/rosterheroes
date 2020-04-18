<?php


namespace App\Domain\Behaviors\TravelTypes;


use App\Domain\Models\Province;

class ContinentTravelBehavior extends TravelTypeBehavior
{

    /**
     * @param Province $currentLocation
     * @return Province
     */
    public function getRandomProvinceToTravelTo(Province $currentLocation): Province
    {
        return Province::query()->where('province_id', '!=', $currentLocation->id)
            ->where('continent_id', '=', $currentLocation->continent_id)
            ->inRandomOrder()->first();
    }
}
