<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Exceptions\BuyItemFromShopException;
use Illuminate\Support\Facades\DB;

class BuyItemFromShop
{
    /**
     * @var AddItemToHasItems
     */
    protected $addItemToHasItems;

    public function __construct(AddItemToHasItems $addItemToHasItems)
    {
        $this->addItemToHasItems = $addItemToHasItems;
    }

    /**
     * @param Item $item
     * @param Shop $shop
     * @param Squad $squad
     * @return ItemCollection
     * @throws BuyItemFromShopException
     */
    public function execute(Item $item, Shop $shop, Squad $squad)
    {
        if (! ($item->ownedByMorphable($shop) && $item->made_shop_available_at )) {
            $message = $item->getItemName() . ' is no longer available';
            throw new BuyItemFromShopException($item, $shop, $squad, $message, BuyItemFromShopException::CODE_INVALID_OWNERSHIP);
        }

        if ($shop->province_id !== $squad->province_id) {
            $message = $squad->name . ' must be at ' . $squad->province->name . ' to shop at ' . $shop->name;
            throw new BuyItemFromShopException($item, $shop, $squad, $message, BuyItemFromShopException::CODE_INVALID_PROVINCE);
        }

        $purchasePrice = $shop->getPurchasePrice($item);
        if ($squad->gold < $purchasePrice) {
            $message = 'Not enough gold';
            throw new BuyItemFromShopException($item, $shop, $squad, $message, BuyItemFromShopException::CODE_NOT_ENOUGH_GOLD);
        }

        return DB::transaction(function () use ($item, $squad, $purchasePrice) {
            $item->made_shop_available_at = null;
            $item->shop_acquired_at = null;
            $item->shop_acquisition_cost = null;
            $squad->gold -= $purchasePrice;
            $squad->save();
            return $this->addItemToHasItems->execute($item, $squad->fresh());
        });
    }
}
