<?php


namespace App\Admin\Content\Actions;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Str;

class CreateAttack
{
    public function execute(
        string $name,
        int $tier,
        ?int $targetsCount,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType)
    {

        $dataArray = json_decode(file_get_contents(resource_path('json/content/attacks.json')), true);
        $dataArray['last_updated'] = now()->timestamp;
        $dataArray['data'][] = [
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'attacker_position_id' => $attackerPosition->id,
            'target_position_id' => $targetPosition->id,
            'target_priority_id' => $targetPriority->id,
            'damage_type_id' => $damageType->id,
            'tier' => $tier,
            'targets_count' => $targetsCount
        ];

        file_put_contents(resource_path('json/content/attacks.json'), json_encode($dataArray, JSON_PRETTY_PRINT));
    }
}
