<?php


namespace App\Domain\Actions;


use App\AttackSnapshot;
use App\Domain\Models\Attack;
use App\Facades\CurrentWeek;

class BuildAttackSnapshotAction
{
    public function execute(Attack $attack): AttackSnapshot
    {
        /** @var AttackSnapshot $snapshot */
        $snapshot = AttackSnapshot::query()->create([
            'attack_id' => $attack->id,
            'week_id' => CurrentWeek::id(),
            'data' => []
        ]);
        return $snapshot;
    }
}
