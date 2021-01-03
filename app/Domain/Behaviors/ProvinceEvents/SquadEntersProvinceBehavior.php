<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\Province;

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
}
