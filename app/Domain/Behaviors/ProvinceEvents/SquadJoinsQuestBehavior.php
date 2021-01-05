<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Quest;
use App\Domain\Models\Week;

class SquadJoinsQuestBehavior extends ProvinceEventBehavior
{

    public function getQuestUuid()
    {
        return $this->extra['quest']['uuid'];
    }

    public static function buildExtraArray(Quest $questJoined, Week $week)
    {
        return [
            'quest' => [
                'uuid' => $questJoined->uuid,
                'name' => $questJoined->name
            ],
            'week' => [
                'uuid' => $week->uuid
            ]
        ];
    }

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [];
    }
}
