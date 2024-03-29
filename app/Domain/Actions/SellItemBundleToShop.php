<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Jobs\CreateSquadSellsItemsEventJob;
use Illuminate\Support\Facades\DB;

class SellItemBundleToShop
{
    /**
     * @var SellItemToShop
     */
    protected $sellItemToShop;

    public function __construct(SellItemToShop $sellItemToShop)
    {
        $this->sellItemToShop = $sellItemToShop;
    }

    /**
     * @param ItemCollection $items
     * @param Squad $squad
     * @param Shop $shop
     * @return ItemCollection
     */
    public function execute(ItemCollection $items, Squad $squad, Shop $shop)
    {
        $itemsSold = DB::transaction(function () use ($items, $squad, $shop) {

            $itemsSold = new ItemCollection();

            $items->each(function (Item $item) use (&$itemsSold, $squad, $shop) {
                $items = $this->sellItemToShop->execute($item, $squad, $shop);
                $itemsSold = $itemsSold->merge($items);
            });

            return $itemsSold;
        });
        $totalGold = $items->sum(function (Item $item) {
            return $item->shop_acquisition_cost ?: 0;
        });
        dispatch(new CreateSquadSellsItemsEventJob($squad, $shop, $shop->province, $items->count(), $totalGold, now()));

        return $itemsSold;
    }
}
