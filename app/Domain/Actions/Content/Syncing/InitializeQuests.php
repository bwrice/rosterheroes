<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Titan;

class InitializeQuests
{
    public function execute()
    {
        $quests = Quest::query()->with([
            'minions',
            'titans',
            'chestBlueprints',
            'sideQuests.sideQuestBlueprint'
        ])->get();
        $data = $quests->map(function (Quest $quest) {
            return [
                'uuid' => $quest->uuid,
                'name' => $quest->name,
                'level' => $quest->level,
                'starting_province_id' => $quest->province_id,
                'travel_type_id' => $quest->travel_type_id,
                'minions' => $quest->minions->map(function (Minion $minion) {
                    return [
                        'uuid' => $minion->uuid,
                        'weight' => $minion->pivot->weight
                    ];
                }),
                'titans' => $quest->titans->map(function (Titan $titan) {
                    return [
                        'uuid' => $titan->uuid,
                        'count' => $titan->pivot->count
                    ];
                }),
                'chest_blueprints' => $quest->chestBlueprints->map(function (ChestBlueprint $chestBlueprint) {
                    return [
                        'uuid' => $chestBlueprint->uuid,
                        'count' => $chestBlueprint->pivot->count,
                        'chance' => $chestBlueprint->pivot->chance
                    ];
                }),
                'side_quest_blueprints' => $quest->sideQuests->map(function (SideQuest $sideQuest) {
                    return [
                        'uuid' => $sideQuest->sideQuestBlueprint->uuid,
                        'count' => 1
                    ];
                })
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/quests.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
