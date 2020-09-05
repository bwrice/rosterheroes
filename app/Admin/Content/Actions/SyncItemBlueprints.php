<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ItemBlueprintSource;
use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Facades\Content;
use Illuminate\Support\Facades\DB;

class SyncItemBlueprints extends SyncContent
{
    protected $dependencies = [
        self::ATTACKS_DEPENDENCY,
        self::ITEM_TYPES_DEPENDENCY
    ];

    /**
     * @return mixed
     * @throws \App\Exceptions\SyncContentException
     */
    public function execute()
    {
        $this->checkDependencies();
        $notSynced = collect();
        $itemBlueprintSources = Content::unSyncedItemBlueprints();

        $itemBlueprintSources->each(function (ItemBlueprintSource $itemBlueprintSource) use ($notSynced) {

            try {
                DB::transaction(function () use ($itemBlueprintSource) {

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
                    $this->syncItemTypes($itemBlueprint, $itemBlueprintSource);
                    $this->syncAttacks($itemBlueprint, $itemBlueprintSource);
                    $this->syncMaterials($itemBlueprint, $itemBlueprintSource);
                    $this->syncEnchantments($itemBlueprint, $itemBlueprintSource);
                });

            } catch (\Exception $exception) {
                $notSynced->push([
                    'source' => $itemBlueprintSource,
                    'exception' => $exception
                ]);
            }
        });

        return $notSynced;
    }

    protected function syncItemTypes(ItemBlueprint $itemBlueprint, ItemBlueprintSource $itemBlueprintSource)
    {
        $itemTypes = ItemType::query()->whereIn('uuid', $itemBlueprintSource->getItemTypes())->get();

        if ($itemTypes->count() !== count($itemBlueprintSource->getItemTypes())) {
            throw new \Exception("Not all item-types found for item-blueprint: " . $itemBlueprint->uuid);
        }

        $itemBlueprint->itemTypes()->sync($itemTypes->pluck('id')->toArray());
    }

    protected function syncAttacks(ItemBlueprint $itemBlueprint, ItemBlueprintSource $itemBlueprintSource)
    {
        $attacks = Attack::query()->whereIn('uuid', $itemBlueprintSource->getAttacks())->get();

        if ($attacks->count() !== count($itemBlueprintSource->getAttacks())) {
            throw new \Exception("Not all attacks found for item-blueprint: " . $itemBlueprint->uuid);
        }

        $itemBlueprint->attacks()->sync($attacks->pluck('id')->toArray());
    }

    protected function syncMaterials(ItemBlueprint $itemBlueprint, ItemBlueprintSource $itemBlueprintSource)
    {
        $materials = Material::query()->whereIn('uuid', $itemBlueprintSource->getMaterials())->get();

        if ($materials->count() !== count($itemBlueprintSource->getMaterials())) {
            throw new \Exception("Not all materials found for item-blueprint: " . $itemBlueprint->uuid);
        }

        $itemBlueprint->materials()->sync($materials->pluck('id')->toArray());
    }

    protected function syncEnchantments(ItemBlueprint $itemBlueprint, ItemBlueprintSource $itemBlueprintSource)
    {
        $enchantments = Enchantment::query()->whereIn('uuid', $itemBlueprintSource->getEnchantments())->get();

        if ($enchantments->count() !== count($itemBlueprintSource->getEnchantments())) {
            throw new \Exception("Not all enchantments found for item-blueprint: " . $itemBlueprint->uuid);
        }

        $itemBlueprint->enchantments()->sync($enchantments->pluck('id')->toArray());
    }
}
