<?php


namespace App\Domain\Actions\ProvinceEvents;


use App\Domain\Behaviors\ProvinceEvents\SquadSellsItemsBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Events\NewProvinceEvent;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CreateSquadSellsItemsEvent
{
    public const RECENT_MINUTES = 10;

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
        /** @var ProvinceEvent|null $provinceEvent */
        $provinceEvent = null;

        ProvinceEvent::query()
            ->where('event_type', '=', ProvinceEvent::TYPE_SQUAD_SELLS_ITEMS)
            ->where('squad_id', '=', $squad->id)
            ->where('province_id', '=', $province->id)
            ->where('happened_at', '>=', $happenedAt->subMinutes(self::RECENT_MINUTES))
            ->chunk(200, function (Collection $provinceEvents) use ($shop, &$provinceEvent) {
                $match = $provinceEvents->first(function (ProvinceEvent $provinceEvent) use ($shop) {
                    /** @var SquadSellsItemsBehavior $behavior */
                    $behavior = $provinceEvent->getBehavior();
                    return $behavior->getShopUuid() === (string) $shop->uuid;
                });

                if ($match) {
                    $provinceEvent = $match;
                    return false; //exit early from chunk
                }
            });

        if ($provinceEvent) {
            /** @var SquadSellsItemsBehavior $behavior */
            $behavior = $provinceEvent->getBehavior();
            $provinceEvent->happened_at = $happenedAt;
            $provinceEvent->extra = $behavior->increaseExtraValues($itemsCount, $gold)->getExtra();
            $provinceEvent->save();
        } else {
            $provinceEvent = ProvinceEvent::query()->create([
                'uuid' => Str::uuid(),
                'event_type' => ProvinceEvent::TYPE_SQUAD_SELLS_ITEMS,
                'squad_id' => $squad->id,
                'province_id' => $province->id,
                'happened_at' => $happenedAt,
                'extra' => SquadSellsItemsBehavior::buildExtraArray($shop, $itemsCount, $gold)
            ]);
        }

        event(new NewProvinceEvent($provinceEvent));
        return $provinceEvent;
    }
}
