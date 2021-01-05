<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Http\Resources\LocalSquadResource;
use App\Http\Resources\ProvinceEventResource;

class SquadEntersProvinceBehavior extends ProvinceEventBehavior
{
    /**
     * @return int
     */
    public function getGoldCost()
    {
        return $this->extra['cost'];
    }

    /**
     * @return string
     */
    public function getProvinceLeftUuid()
    {
        return $this->extra['from']['uuid'];
    }

    /**
     * @param Province $provinceLeft
     * @param int $goldCost
     * @return array
     */
    public static function buildExtraArray(Province $provinceLeft, int $goldCost)
    {
        return [
            'from' => [
                'uuid' => $provinceLeft->uuid,
                'name' => $provinceLeft->name
            ],
            'cost' => $goldCost
        ];
    }

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [
            'localSquad' => new LocalSquadResource($provinceEvent->squad)
        ];
    }
}
