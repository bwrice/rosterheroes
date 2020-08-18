<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;

class InitializeMinions
{
    public function execute()
    {
        $minions = Minion::query()->with([
            'enemyType',
            'combatPosition',
            'attacks',
            'chestBlueprints'
        ])->get();
        $data = $minions->map(function (Minion $minion) {
            return [
                'name' => $minion->name,
                'uuid' => $minion->uuid,
                'level' => $minion->level,
                'enemy_type' => $minion->enemy_type_id,
                'combat_position' => $minion->combat_position_id,
                'attacks' => $minion->attacks->pluck('uuid')->values(),
                'chest_blueprints' => $minion->chestBlueprints->map(function (ChestBlueprint $chestBlueprint) {
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
        file_put_contents(resource_path('json/content/minions.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
