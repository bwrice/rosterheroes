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
    protected $backInventory;
    /**
     * @var GenerateItemFromBlueprintAction
     */
    private $generateItemFromBlueprintAction;

    public function __construct(GenerateItemFromBlueprintAction $generateItemFromBlueprintAction)
    {
        $this->generateItemFromBlueprintAction = $generateItemFromBlueprintAction;
    }

    /**
     * @param Shop $shop
     * @return int
     * @throws \Exception
     */
    public function execute(Shop $shop)
    {
        $this->shop = $shop;
        $this->stockCapacity = $this->shop->getStockCapacity();
        $this->backInventoryCapacity = $this->shop->getBackInventoryCapacity();

        $this->backInventory = $this->shop->items()->whereNull('made_shop_available_at')->count();

        $backInventoryDiff = $this->stockCapacity - $this->backInventory;

        if ($backInventoryDiff <= 0) {

            $this->stockShelvesFromCurrentInventory();
            return 0;
        }

        $blueprints = $this->shop->getStockFillingBlueprints();

        if ($blueprints->isEmpty()) {
            throw new \Exception("No blueprints found for restocking shop: " . $this->shop->id);
        }

        for ($i = 1; $i <= $backInventoryDiff; $i++) {
            $blueprint = $blueprints->random();
            $item = $this->generateItemFromBlueprintAction->execute($blueprint);
            $item->shop_acquired_at = Date::now();
            $item->shop_acquisition_cost = $item->getValue();
            $item->hasItems()->associate($this->shop);
            $item->save();
        }

        $this->stockShelvesFromCurrentInventory();
        return $backInventoryDiff;
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
