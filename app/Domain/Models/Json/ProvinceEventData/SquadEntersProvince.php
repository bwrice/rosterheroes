<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;

class SquadEntersProvince extends ProvinceEventData
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
