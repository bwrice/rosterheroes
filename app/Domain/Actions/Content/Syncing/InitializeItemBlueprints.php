<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ItemBlueprint;

class InitializeItemBlueprints
{
    public function execute()
    {
        $itemBlueprints = ItemBlueprint::query()->with([
            'enchantments',
            'itemTypes',
            'itemBases',
            'itemClasses',
            'materials',
            'attacks'
        ])->get();
        $data = $itemBlueprints->map(function (ItemBlueprint $itemBlueprint) {
            return [
                'item_name' => $itemBlueprint->item_name,
                'description' => $itemBlueprint->description,
                'uuid' => $itemBlueprint->uuid,
                'enchantment_power' => $itemBlueprint->enchantment_power,
                'attacks' => $itemBlueprint->attacks->pluck('uuid')->values(),
                'item_types' => $itemBlueprint->itemTypes->pluck('id')->toArray(),
                'item_bases' => $itemBlueprint->itemBases->pluck('id')->toArray(),
                'item_classes' => $itemBlueprint->itemClasses->pluck('id')->toArray(),
                'materials' => $itemBlueprint->materials->pluck('id')->toArray(),
                'enchantments' => $itemBlueprint->enchantments->pluck('id')->toArray()
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/item_blueprints.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
