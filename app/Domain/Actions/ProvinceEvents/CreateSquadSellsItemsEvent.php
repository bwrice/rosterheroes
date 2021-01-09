<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadSellsItemsBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class CreateSquadSellsItemsEvent
{
    /**
     * @param Squad $squad
     * @param Shop $shop
     * @param Province $province
     * @param int $itemsCount
     * @param int $gold
     * @param CarbonInterface $happenedAt
     * @return ProvinceEvent
     */
    public function execute(
        Squad $squad,
        Shop $shop,
        Province $province,
        int $itemsCount,
        int $gold,
        CarbonInterface $happenedAt)
    {
        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create([
            'uuid' => Str::uuid(),
            'event_type' => ProvinceEvent::TYPE_SQUAD_SELLS_ITEMS,
            'squad_id' => $squad->id,
            'province_id' => $province->id,
            'happened_at' => $happenedAt,
            'extra' => SquadSellsItemsBehavior::buildExtraArray($shop, $itemsCount, $gold)
        ]);

        return $provinceEvent;
    }
}
