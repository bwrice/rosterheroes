<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Exceptions\SellItemToShopException;
use Illuminate\Support\Facades\DB;

class SellItemToShop
{

    /**
     * @param Item $item
     * @param Squad $squad
     * @param Shop $shop
     * @throws SellItemToShopException
     */
    public function execute(Item $item, Squad $squad, Shop $shop)
    {
        if (! $item->ownedByMorphable($squad)) {
            $message = $item->getItemName() . ' does not belong to ' . $squad->name;
            throw new SellItemToShopException($item, $squad, $shop, $message, SellItemToShopException::CODE_INVALID_ITEM_OWNERSHIP);
        }

        if ($squad->province_id !== $shop->province_id) {
            $message = $squad->name . ' is not in the province of ' . $shop->province->name;
            throw new SellItemToShopException($item, $squad, $shop, $message, SellItemToShopException::CODE_INVALID_PROVINCE);
        }

        $salePrice = $shop->getSalePrice($item);

        DB::transaction(function () use ($item, $squad, $shop, $salePrice) {

            $squad->gold += $salePrice;
            $squad->save();
            $item->hasItems()->associate($shop);
            $item->save();
        });
    }


}
