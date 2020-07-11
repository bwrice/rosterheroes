<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuestBlueprint;

class InitializeSideQuestBlueprints
{

    public function execute()
    {
        $sideQuestBlueprints = SideQuestBlueprint::query()->with([
            'minions',
            'chestBlueprints'
        ])->get();
        $data = $sideQuestBlueprints->map(function (SideQuestBlueprint $sideQuestBlueprint) {
            return [
                'uuid' => $sideQuestBlueprint->uuid,
                'name' => $sideQuestBlueprint->name,
                'minions' => $sideQuestBlueprint->minions->map(function (Minion $minion) {
                    return [
                        'uuid' => $minion->uuid,
                        'count' => $minion->pivot->count
                    ];
                }),
                'chest_blueprints' => $sideQuestBlueprint->chestBlueprints->map(function (ChestBlueprint $chestBlueprint) {
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
        file_put_contents(resource_path('json/content/side_quest_blueprints.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
