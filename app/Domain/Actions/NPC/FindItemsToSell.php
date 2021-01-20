<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Continent;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FindItemsToSell
{
    public function execute(Squad $npc)
    {
        // Want to get mobile-storage down to 1/3rd capacity
        $capacityToRemove = $npc->getMobileStorageCapacityUsed() - (int) ceil((1/3) * $npc->getMobileStorageCapacity());
        if ($capacityToRemove <= 0) {
            return null;
        }

        $itemsToSell = $this->getItemsToSell($npc->items, $capacityToRemove);
        $shop = $this->findShop($npc);
        return [
            'shop' => $shop,
            'items' => new ItemCollection($itemsToSell)
        ];
    }

    protected function getItemsToSell(Collection $items, int $capacityToRemove)
    {
        $prioritizedItems = $items
            ->load(['enchantments.measurableBoosts', 'itemType'])
            ->shuffle()
            ->sortBy(function (Item $item) {
                return $item->enchantmentQuality()['value'] + $item->itemType->tier;
            });
        $itemsToSell = collect();
        while ($capacityToRemove > 0) {
            /** @var Item $item */
            $item = $prioritizedItems->shift();
            if ($item) {
                $capacityToRemove -= $item->weight();
                $itemsToSell->push($item);
            } else {
                $capacityToRemove = 0;
            }
        }
        return $itemsToSell;
    }

    /**
     * @param Squad $npc
     * @return Shop
     */
    protected function findShop(Squad $npc)
    {
        // Prioritize finding a shop in the continent the NPC is located
        $continentID = $npc->province->continent_id;
        $shop = Shop::query()->whereHas('province', function (Builder $builder) use ($continentID) {
            return $builder->where('continent_id', '=', $continentID);
        })->inRandomOrder()->first();

        /** @var Shop|null $shop */
        if ($shop) {
            return $shop;
        }

        // Find shop in any continents the NPC can visit
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npc) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npc->level();
        })->pluck('id')->toArray();

        $shop = Shop::query()->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
            return $builder->where('continent_id', '=', $validContinentIDs);
        })->inRandomOrder()->first();

        return $shop;
    }
}
