<?php


namespace App\Domain\Actions\Content\Syncing;


use App\Domain\Models\Attack;

class InitializeAttacks
{
    public function execute()
    {

        $attacks = Attack::query()->get();
        $data = $attacks->map(function (Attack $attack) {
            return [
                'uuid' => $attack->uuid,
                'name' => $attack->name,
                'attacker_position_id' => $attack->attacker_position_id,
                'target_position_id' => $attack->target_position_id,
                'target_priority_id' => $attack->target_priority_id,
                'damage_type_id' => $attack->damage_type_id,
                'tier' => $attack->tier,
                'targets_count' => $attack->targets_count
            ];
        })->values();
        $contents = [
            'last_updated' => now()->timestamp,
            'data' => $data
        ];
        file_put_contents(resource_path('json/content/attacks.json'), json_encode($contents, JSON_PRETTY_PRINT));
    }
}
