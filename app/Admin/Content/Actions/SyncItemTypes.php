<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\ItemType;
use App\Facades\Content;

class SyncItemTypes
{
    public function execute()
    {
        $unSyncedSources = Content::unSyncedItemTypes();

        $unSyncedSources->each(function (ItemTypeSource $itemTypeSource) {
            /** @var ItemType $itemType */
            $itemType = ItemType::query()->updateOrCreate([
                'uuid' => $itemTypeSource->getUuid()
            ], [
                'name' => $itemTypeSource->getName(),
                'tier' => $itemTypeSource->getTier(),
                'item_base_id' => $itemTypeSource->getItemBaseID()
            ]);

            $attacks = Attack::query()->whereIn('uuid', $itemTypeSource->getAttackUuids())->get();

            $itemType->attacks()->sync($attacks->pluck('id')->toArray());
        });

        return $unSyncedSources;
    }
}
