<?php

use App\Domain\Models\ItemBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedFetroyaStartingShops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $shops = collect([
            [
                'name' => "Gryndor's Goods & Gear",
                'tier' => 1,
                'province' => 'Baygua',
                'item_blueprints' => [
                    ItemBlueprint::GENERIC_LOW_TIER_DAGGER,
                    ItemBlueprint::GENERIC_LOW_TIER_SWORD,
                    ItemBlueprint::GENERIC_LOW_TIER_AXE,
                    ItemBlueprint::GENERIC_LOW_TIER_BOW,
                    ItemBlueprint::GENERIC_LOW_TIER_POLEARM,
                    ItemBlueprint::GENERIC_LOW_TIER_WAND,
                    ItemBlueprint::GENERIC_LOW_TIER_STAFF,
                    ItemBlueprint::GENERIC_LOW_TIER_SHIELD,
                    ItemBlueprint::GENERIC_LOW_TIER_HELMET,
                    ItemBlueprint::GENERIC_LOW_TIER_HEAVY_ARMOR,
                    ItemBlueprint::GENERIC_LOW_TIER_LIGHT_ARMOR,
                    ItemBlueprint::GENERIC_LOW_TIER_ROBES,
                    ItemBlueprint::GENERIC_LOW_TIER_GLOVES,
                    ItemBlueprint::GENERIC_LOW_TIER_GAUNTLETS,
                    ItemBlueprint::GENERIC_LOW_TIER_SHOES,
                    ItemBlueprint::GENERIC_LOW_TIER_BOOTS,
                    ItemBlueprint::GENERIC_LOW_TIER_BELT,
                    ItemBlueprint::GENERIC_LOW_TIER_SASH,
                    ItemBlueprint::GENERIC_LOW_TIER_LEGGINGS,
                ]
            ],
            [
                'name' => "The Soldier Shoppe",
                'tier' => 1,
                'province' => 'Vyspen',
                'item_blueprints' => [
                    ItemBlueprint::GENERIC_LOW_TIER_DAGGER,
                    ItemBlueprint::GENERIC_LOW_TIER_SWORD,
                    ItemBlueprint::GENERIC_LOW_TIER_AXE,
                    ItemBlueprint::GENERIC_LOW_TIER_BOW,
                    ItemBlueprint::GENERIC_LOW_TIER_POLEARM,
                    ItemBlueprint::GENERIC_LOW_TIER_WAND,
                    ItemBlueprint::GENERIC_LOW_TIER_STAFF,
                    ItemBlueprint::GENERIC_LOW_TIER_SHIELD,
                    ItemBlueprint::GENERIC_LOW_TIER_HELMET,
                    ItemBlueprint::GENERIC_LOW_TIER_HEAVY_ARMOR,
                    ItemBlueprint::GENERIC_LOW_TIER_LIGHT_ARMOR,
                    ItemBlueprint::GENERIC_LOW_TIER_ROBES,
                    ItemBlueprint::GENERIC_LOW_TIER_GLOVES,
                    ItemBlueprint::GENERIC_LOW_TIER_GAUNTLETS,
                    ItemBlueprint::GENERIC_LOW_TIER_SHOES,
                    ItemBlueprint::GENERIC_LOW_TIER_BOOTS,
                    ItemBlueprint::GENERIC_LOW_TIER_BELT,
                    ItemBlueprint::GENERIC_LOW_TIER_SASH,
                    ItemBlueprint::GENERIC_LOW_TIER_LEGGINGS,
                ]
            ],
        ]);

        /** @var \App\Domain\Actions\CreateShop $action */
        $action = app(\App\Domain\Actions\CreateShop::class);

        $shops->each(function ($shopData) use ($action) {
            $province = \App\Domain\Models\Province::query()->where('name', '=', $shopData['province'])->first();
            $blueprints = ItemBlueprint::query()->whereIn('reference_id', $shopData['item_blueprints'])->get();
            $action->execute($shopData['name'], $shopData['tier'], $province, $blueprints);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
