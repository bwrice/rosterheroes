<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;

class SquadEntersProvince extends ProvinceEventData
{
    /**
     * @return string
     */
    public function squadUuid()
    {
        return $this->extra['squad']['uuid'];
    }

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
     * @param Squad $squad
     * @param Province $provinceLeft
     * @param int $goldCost
     * @return array
     */
    public static function buildDataArray(Squad $squad, Province $provinceLeft, int $goldCost)
    {
        return [
            'squad' => [
                'uuid' => $squad->uuid,
                'name' => $squad->name
            ],
            'province_left' => [
                'uuid' => $provinceLeft->uuid,
                'name' => $provinceLeft->name
            ],
            'gold_cost' => $goldCost
        ];
    }

}
