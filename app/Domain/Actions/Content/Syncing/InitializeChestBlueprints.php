<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;

class InitializeChestBlueprints
{
    public function execute()
    {
        $chestBlueprints = ChestBlueprint::query()->with([
            'itemBlueprints',
        ])->get();
        $data = $chestBlueprints->map(function (ChestBlueprint $chestBlueprint) {
            return [
                'uuid' => $chestBlueprint->uuid,
                'description' => $chestBlueprint->description,
                'quality' => $chestBlueprint->quality,
                'size' => $chestBlueprint->size,
                'min_gold' => $chestBlueprint->min_gold,
                'max_gold' => $chestBlueprint->max_gold,
                'item_blueprints' => $chestBlueprint->itemBlueprints->map(function (ItemBlueprint $itemBlueprint) {
                    return [
                        'uuid' => $itemBlueprint->uuid,
                        'count' => $itemBlueprint->pivot->count,
                        'chance' => $itemBlueprint->pivot->chance
                    ];
                })
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/chest_blueprints.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
