<?php


namespace App\Domain\Behaviors\ProvinceEvents;


use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Shop;

class SquadSellsItemsBehavior extends ProvinceEventBehavior
{
    public function getShopUuid()
    {
        return $this->extra['shop']['uuid'];
    }

    public function getItemsCount()
    {
        return $this->extra['itemsCount'];
    }

    public function getGold()
    {
        return $this->extra['gold'];
    }

    public static function buildExtraArray(Shop $shop, int $itemsCount, int $gold)
    {
        return [
            'shop' => [
                'uuid' => $shop->uuid,
                'name' => $shop->getName()
            ],
            'itemsCount' => $itemsCount,
            'gold' => $gold
        ];
    }

    public function getSupplementalResourceData(ProvinceEvent $provinceEvent): array
    {
        return [];
    }
}
