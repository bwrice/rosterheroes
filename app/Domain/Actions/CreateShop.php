<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemBlueprintCollection;
use App\Domain\Models\Province;
use App\Domain\Models\Shop;
use Illuminate\Support\Str;

class CreateShop
{
    /**
     * @param string $name
     * @param int $tier
     * @param Province $province
     * @param ItemBlueprintCollection $itemBlueprints
     * @return Shop
     */
    public function execute(string $name, int $tier, Province $province, ItemBlueprintCollection $itemBlueprints)
    {
        /** @var Shop $shop */
        $shop = Shop::query()->create([
            'uuid' => Str::uuid(),
            'name' => $name,
            'province_id' => $province->id,
            'tier' => $tier
        ]);

        $shop->itemBlueprints()->saveMany($itemBlueprints);

        return $shop;
    }
}
