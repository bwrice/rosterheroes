<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Quest;

class SquadJoinsQuestBehavior extends ProvinceEventBehavior
{

    public function getQuestUuid()
    {
        return $this->extra['quest']['uuid'];
    }

    public static function buildExtraArray(Quest $questJoined)
    {
        return [
            'quest' => [
                'uuid' => $questJoined->uuid,
                'name' => $questJoined->name
            ]
        ];
    }

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [];
    }
}
