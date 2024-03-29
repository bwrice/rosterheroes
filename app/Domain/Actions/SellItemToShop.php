<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Exceptions\SellItemToShopException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SellItemToShop
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
     * @param Squad $squad
     * @param Shop $shop
     * @throws SellItemToShopException
     * @return ItemCollection
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

        return DB::transaction(function () use ($item, $squad, $shop, $salePrice) {

            $squad->gold += $salePrice;
            $squad->save();

            $item->shop_acquired_at = Date::now();
            $item->shop_acquisition_cost = $salePrice;
            return $this->addItemToHasItems->execute($item, $shop);
        });
    }


}
