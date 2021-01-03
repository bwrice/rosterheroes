<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadLeavesProvinceBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Squad;
use App\Events\ProvinceEventCreated;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class CreateSquadLeavesProvinceEvent
{
    public function execute(Province $provinceLeft, Province $provinceEntered, Squad $squad, CarbonInterface $happenedAt)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => (string) Str::uuid(),
            'province_id' => $provinceLeft->id,
            'squad_id' => $squad->id,
            'event_type' => ProvinceEvent::TYPE_SQUAD_LEAVES_PROVINCE,
            'happened_at' => $happenedAt,
            'extra' => SquadLeavesProvinceBehavior::buildExtraArray($provinceEntered)
        ]);

        event(new ProvinceEventCreated($provinceEvent));
        return $provinceEvent;
    }
}
