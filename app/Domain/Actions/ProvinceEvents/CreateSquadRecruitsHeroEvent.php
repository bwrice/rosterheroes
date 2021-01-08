<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadRecruitsHeroBehavior;
use App\Domain\Models\Hero;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Events\NewProvinceEvent;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class CreateSquadRecruitsHeroEvent
{
    public function execute(
        Squad $squad,
        Hero $hero,
        RecruitmentCamp $recruitmentCamp,
        Province $province,
        CarbonInterface $happenedAt)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => Str::uuid(),
            'squad_id' => $squad->id,
            'event_type' => ProvinceEvent::TYPE_SQUAD_RECRUITS_HERO,
            'province_id' => $province->id,
            'happened_at' => $happenedAt,
            'extra' => SquadRecruitsHeroBehavior::buildExtraArray($hero, $recruitmentCamp)
        ]);

        event(new NewProvinceEvent($provinceEvent));
        return $provinceEvent;
    }
}
