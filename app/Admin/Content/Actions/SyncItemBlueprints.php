<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ItemBlueprintSource;
use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Facades\Content;
use Illuminate\Support\Facades\DB;

class SyncItemBlueprints
{
    public function execute()
    {
        if (Content::unSyncedAttacks()->isNotEmpty()) {
            throw new \Exception("Cannot sync item-blueprints while attacks are out of sync");
        }

        return DB::transaction(function () {

            $itemBlueprintSources = Content::unSyncedItemBlueprints();

            $itemBlueprintSources->each(function (ItemBlueprintSource $itemBlueprintSource) {
                /** @var ItemBlueprint $itemBlueprint */
                $itemBlueprint = ItemBlueprint::query()->updateOrCreate([
                    'uuid' => $itemBlueprintSource->getUuid()
                ], [
                    'item_name' => $itemBlueprintSource->getItemName(),
                    'description' => $itemBlueprintSource->getDescription(),
                    'enchantment_power' => $itemBlueprintSource->getEnchantmentPower()
                ]);

                $itemBlueprint->itemBases()->sync($itemBlueprintSource->getItemBases());
                $itemBlueprint->itemClasses()->sync($itemBlueprintSource->getItemClasses());

                $itemTypes = ItemType::query()->whereIn('uuid', $itemBlueprintSource->getItemTypes())->get();

                if ($itemTypes->count() !== count($itemBlueprintSource->getItemTypes())) {
                    throw new \Exception("Not all item-types found for item-blueprint: " . $itemBlueprint->uuid);
                }

                $itemBlueprint->itemTypes()->sync($itemTypes->pluck('id')->toArray());

                $attacks = Attack::query()->whereIn('uuid', $itemBlueprintSource->getAttacks())->get();

                if ($attacks->count() !== count($itemBlueprintSource->getAttacks())) {
                    throw new \Exception("Not all attacks found for item-blueprint: " . $itemBlueprint->uuid);
                }

                $itemBlueprint->attacks()->sync($attacks->pluck('id')->toArray());

                $materials = Material::query()->whereIn('uuid', $itemBlueprintSource->getMaterials())->get();

                if ($materials->count() !== count($itemBlueprintSource->getMaterials())) {
                    throw new \Exception("Not all materials found for item-blueprint: " . $itemBlueprint->uuid);
                }

                $itemBlueprint->materials()->sync($materials->pluck('id')->toArray());

                $enchantments = Enchantment::query()->whereIn('uuid', $itemBlueprintSource->getEnchantments())->get();

                if ($enchantments->count() !== count($itemBlueprintSource->getEnchantments())) {
                    throw new \Exception("Not all enchantments found for item-blueprint: " . $itemBlueprint->uuid);
                }

                $itemBlueprint->enchantments()->sync($enchantments->pluck('id')->toArray());
            });

            return $itemBlueprintSources;
        });
    }
}
