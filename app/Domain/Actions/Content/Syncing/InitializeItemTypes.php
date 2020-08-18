<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\Attack;
use App\Domain\Models\ItemType;

class InitializeItemTypes
{
    public function execute()
    {

        $itemTypes = ItemType::query()->get();
        $data = $itemTypes->map(function (ItemType $itemType) {
            return [
                'uuid' => $itemType->uuid,
                'name' => $itemType->name,
                'tier' => $itemType->tier,
                'item_base_id' => $itemType->item_base_id,
                'attacks' => $itemType->attacks->map(function(Attack $attack) {
                    return $attack->uuid;
                }),
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/item_types.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
