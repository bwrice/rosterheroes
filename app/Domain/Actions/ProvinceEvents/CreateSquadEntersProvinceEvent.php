<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadEntersProvinceBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Squad;
use App\Events\NewProvinceEvent;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class CreateSquadEntersProvinceEvent
{

    /**
     * @param Province $provinceEntered
     * @param Province $provinceLeft
     * @param Squad $squad
     * @param CarbonInterface $happenedAt
     * @param int $goldCost
     * @return ProvinceEvent
     */
    public function execute(Province $provinceEntered, Province $provinceLeft, Squad $squad, CarbonInterface $happenedAt, int $goldCost)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => (string) Str::uuid(),
            'province_id' => $provinceEntered->id,
            'squad_id' => $squad->id,
            'event_type' => ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE,
            'happened_at' => $happenedAt,
            'extra' => SquadEntersProvinceBehavior::buildExtraArray($provinceLeft, $goldCost)
        ]);

        event(new NewProvinceEvent($provinceEvent));
        return $provinceEvent;
    }
}
