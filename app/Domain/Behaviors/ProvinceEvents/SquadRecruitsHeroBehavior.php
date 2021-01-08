<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\Hero;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\RecruitmentCamp;

class SquadRecruitsHeroBehavior extends ProvinceEventBehavior
{
    public function getRecruitmentCampUuid()
    {
        return $this->extra['recruitmentCamp']['uuid'];
    }

    public function getHeroUuid()
    {
        return $this->extra['hero']['uuid'];
    }

    public static function buildExtraArray(Hero $hero, RecruitmentCamp $recruitmentCamp)
    {
        return [
            'recruitmentCamp' => [
                'uuid' => $recruitmentCamp->uuid,
                'name' => $recruitmentCamp->name
            ],
            'hero' => [
                'uuid' => $hero->uuid,
                'name' => $hero->name,
                'heroClassID' => $hero->hero_class_id,
                'heroRaceID' => $hero->hero_race_id
            ]
        ];
    }

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [];
    }
}
