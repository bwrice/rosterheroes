<?php


namespace App\Domain\Actions;


use App\Domain\Models\SideQuestResult;
use App\Facades\CurrentWeek;
use App\Jobs\AttachSnapshotsToSideQuestResultJob;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;

class AddAttachSnapshotToSideQuestResultJobsToBatch
{
    /**
     * @param Batch $batch
     * @param int $startRangeID
     * @param int $endRangeID
     * @return Batch
     */
    public function execute(Batch $batch, int $startRangeID, int $endRangeID)
    {
        $jobs = SideQuestResult::query()
            ->whereBetween('id', [$startRangeID, $endRangeID])
            ->whereNull(['squad_snapshot_id', 'side_quest_snapshot_id'])
            ->whereHas('campaignStop', function (Builder $builder) {
                $builder->whereHas('campaign', function (Builder $builder) {
                    return $builder->where('week_id', '=', CurrentWeek::id());
                });
            })->get()->map(function (SideQuestResult $sideQuestResult) {
                return new AttachSnapshotsToSideQuestResultJob($sideQuestResult);
            });

        $batch->add($jobs);
        return $batch;
    }
}
