<?php


namespace App\Domain\Actions;


use App\Domain\Models\Shop;
use Illuminate\Support\Facades\Date;

class UpdateShopStock
{
    /** @var Shop */
    protected $shop;
    /** @var int */
    protected $stockCapacity;
    /** @var int */
    protected $backInventoryCapacity;
    /** @var int */
    protected $totalInventory;

    public function execute(Shop $shop)
    {
        $this->shop = $shop;
        $this->stockCapacity = $this->shop->getStockCapacity();
        $this->backInventoryCapacity = $this->shop->getBackInventoryCapacity();

        $this->totalInventory = $this->shop->items()->count();

        if ($this->totalInventory >= ($this->stockCapacity + $this->backInventoryCapacity)) {
            $this->stockShelvesFromCurrentInventory();
        }
    }

    protected function stockShelvesFromCurrentInventory()
    {
        $previouslyAvailableIDs = $this->shop->availableItems()->pluck('id')->toArray();
        $this->shop->availableItems()->update([
            'made_shop_available_at' => null
        ]);

        $this->shop->items()->whereNull('made_shop_available_at')
            ->whereNotIn('id', $previouslyAvailableIDs)
            ->take($this->stockCapacity)
            ->update([
                'made_shop_available_at' => Date::now()
            ]);
    }

}
