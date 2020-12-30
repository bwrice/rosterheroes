<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

class CreateSquadEntersProvinceEvent
{
    /**
     * @param Province $province
     * @param int $squadID
     * @param CarbonInterface $happenedAt
     * @param int $goldCost
     * @return ProvinceEvent
     */
    public function execute(Province $province, int $squadID, CarbonInterface $happenedAt, int $goldCost)
    {
        $dataObject = new SquadEntersProvince($province, $happenedAt, $squadID, $goldCost);
        /** @var ProvinceEvent $event */
        $event = ProvinceEvent::query()->create([
            'province_id' => $province->id,
            'event_type' => ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE,
            'happened_at' => $happenedAt,
            'data' => $dataObject
        ]);
        return $event;
    }
}
