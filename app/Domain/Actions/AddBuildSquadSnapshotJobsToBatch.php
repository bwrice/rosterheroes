<?php


namespace App\Domain\Actions;


use App\Domain\Models\Squad;
use App\Facades\CurrentWeek;
use App\Jobs\BuildSquadSnapshotJob;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;

class AddBuildSquadSnapshotJobsToBatch
{
    /**
     * @param Batch $batch
     * @param int $startRangeID
     * @param int $endRangeID
     * @return Batch
     */
    public function execute(Batch $batch, int $startRangeID, int $endRangeID)
    {
        if ($batch->cancelled()) {
            return $batch;
        }

        $jobs = Squad::query()->whereBetween('id', [$startRangeID, $endRangeID])
            ->whereHas('campaigns', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            })->whereDoesntHave('squadSnapshots', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            })->get()->map(function (Squad $squad) {
                return new BuildSquadSnapshotJob($squad);
            });

        $batch->add($jobs);
        return $batch;
    }
}
