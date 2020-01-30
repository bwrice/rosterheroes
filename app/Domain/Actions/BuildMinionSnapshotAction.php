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
        /** @var MinionSnapshot $snapshot */
        $snapshot = MinionSnapshot::query()->create([
            'minion_id' => $minion->id,
            'week_id' => CurrentWeek::id(),
            'data' => []
        ]);
        return $snapshot;
    }
}
