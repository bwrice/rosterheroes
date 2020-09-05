<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Titan;

class InitializeTitans
{
    public function execute()
    {
        $titans = Titan::query()->get();
        $data = $titans->map(function (Titan $titan) {
            return [
                'uuid' => $titan->uuid,
                'name' => $titan->name,
                'level' => $titan->level,
                'combat_position' => $titan->combat_position_id,
                'attacks' => $titan->attacks->pluck('uuid')->values(),
                'chest_blueprints' => $titan->chestBlueprints->map(function (ChestBlueprint $chestBlueprint) {
                    return [
                        'uuid' => $chestBlueprint->uuid,
                        'count' => $chestBlueprint->pivot->count,
                        'chance' => $chestBlueprint->pivot->chance
                    ];
                })
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/titans.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
