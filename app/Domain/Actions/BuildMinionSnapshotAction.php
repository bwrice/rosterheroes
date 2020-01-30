<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\Facades\CurrentWeek;
use App\MinionSnapshot;

class BuildMinionSnapshotAction
{
    /**
     * @param Minion $minion
     * @return MinionSnapshot
     */
    public function execute(Minion $minion): MinionSnapshot
    {
        $currentWeekID = CurrentWeek::id();

        /** @var MinionSnapshot $existing */
        $existing = MinionSnapshot::query()
            ->where('minion_id', '=', $minion->id)
            ->where('week_id', '=', $currentWeekID)
            ->first();
        if ($existing) {
            return $existing;
        }

        /** @var MinionSnapshot $snapshot */
        $snapshot = MinionSnapshot::query()->create([
            'minion_id' => $minion->id,
            'week_id' => $currentWeekID,
            'data' => []
        ]);
        return $snapshot;
    }
}
