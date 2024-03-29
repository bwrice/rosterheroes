<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Http\Resources\LocalSquadResource;
use App\Http\Resources\ProvinceEventResource;

class SquadLeavesProvinceBehavior extends ProvinceEventBehavior
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

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [];
    }
}
