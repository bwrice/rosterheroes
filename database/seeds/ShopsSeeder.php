<?php

use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Province;
use Illuminate\Database\Seeder;

class ShopsSeeder extends Seeder
{
    /**
     * @param GenerateItemFromBlueprintAction $generateItemFromBlueprintAction
     */
    public function run(GenerateItemFromBlueprintAction $generateItemFromBlueprintAction)
    {
        $provinces = Province::query()
            ->where('continent_id', '=', 1)
            ->inRandomOrder()->take(15)->get();

        /** @var ItemBlueprint $blueprint */
        $blueprint = ItemBlueprint::query()->where('reference_id', '=', ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM)->first();
        $shopFactory = \App\Factories\Models\ShopFactory::new();

        $provinces->each(function (Province $province) use ($generateItemFromBlueprintAction, $blueprint, $shopFactory) {

            $shop = $shopFactory->withProvinceID($province->id)->create();

            $now = \Illuminate\Support\Facades\Date::now();
            $itemsCount = rand(5, 15);
            for($i = 1; $i <= $itemsCount; $i++) {
                $item = $generateItemFromBlueprintAction->execute($blueprint);
                $item->made_shop_available_at = $now;
                $item->hasItems()->associate($shop);
                $item->save();
            }
        });
    }
}
