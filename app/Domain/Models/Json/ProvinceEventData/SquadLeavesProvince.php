<?php


namespace App\Domain\Models\Json\ProvinceEventData;


use App\Domain\Models\Province;

class SquadLeavesProvince extends ProvinceEventData
{
    /**
     * @return string
     */
    public function getProvinceToUuid()
    {
        return $this->extra['to']['uuid'];
    }

    /**
     * @param Province $provinceEntered
     * @return array
     */
    public static function buildExtraArray(Province $provinceEntered)
    {
        return [
            'to' => [
                'uuid' => $provinceEntered->uuid,
                'name' => $provinceEntered->name
            ]
        ];
    }
}
