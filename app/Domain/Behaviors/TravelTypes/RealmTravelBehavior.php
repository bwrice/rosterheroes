<?php


namespace App\Domain\Behaviors\TravelTypes;


use App\Domain\Models\Province;

class RealmTravelBehavior extends TravelTypeBehavior
{

    /**
     * @param Province $currentLocation
     * @return Province
     */
    public function getRandomProvinceToTravelTo(Province $currentLocation): Province
    {
        return Province::query()->where('id', '!=', $currentLocation->id)->inRandomOrder()->first();
    }
}
