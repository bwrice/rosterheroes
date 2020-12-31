<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class SquadEntersProvince extends ProvinceEventData
{

    /**
     * @return int
     */
    public function getGoldCost()
    {
        return $this->extra['gold_cost'];
    }

    /**
     * @return string
     */
    public function getProvinceLeftUuid()
    {
        return $this->extra['province_left']['uuid'];
    }

    /**
     * @param Province $provinceLeft
     * @param int $goldCost
     * @return array
     */
    public static function buildExtraArray(Province $provinceLeft, int $goldCost)
    {
        return [
            'province_left' => [
                'uuid' => $provinceLeft->uuid,
                'name' => $provinceLeft->name
            ],
            'gold_cost' => $goldCost
        ];
    }

}
