<?php


namespace App\Domain\Actions;


use App\AttackSnapshot;
use App\Domain\Models\Attack;
use App\Facades\AttackService;
use App\Facades\CurrentWeek;

class BuildAttackSnapshotAction
{
    public function execute(Attack $attack): AttackSnapshot
    {
        $currentWeekID = CurrentWeek::id();

        /** @var AttackSnapshot $existing */
        $existing = AttackSnapshot::query()
            ->where('attack_id', '=', $attack->id)
            ->where('week_id', '=', $currentWeekID)->first();
        if ($existing) {
            return $existing;
        }

        /** @var AttackSnapshot $snapshot */
        $snapshot = AttackSnapshot::query()->create([
            'attack_id' => $attack->id,
            'week_id' => $currentWeekID,
            'data' => []
        ]);
        return $snapshot;
    }
}
