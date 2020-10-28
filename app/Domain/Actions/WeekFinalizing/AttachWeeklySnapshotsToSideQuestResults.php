<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\SideQuestResult;
use App\Facades\CurrentWeek;
use App\Jobs\AttachSnapshotsToSideQuestResultsForGroupJob;
use App\Jobs\FinalizeWeekJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;

class AttachWeeklySnapshotsToSideQuestResults implements FinalizeWeekDomainAction
{
    protected int $groupSize = 200;

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $jobs = collect();

        SideQuestResult::query()
            ->whereHas('campaignStop.campaign', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            })->orderBy('id')
            ->select(['id'])
            ->chunk($this->groupSize, function (Collection $sideQuestResults) use ($jobs) {
                $startRangeID = $sideQuestResults->first()->id;
                $endRangeID = $sideQuestResults->last()->id;
                $jobs->push(new AttachSnapshotsToSideQuestResultsForGroupJob($startRangeID, $endRangeID));
            });

        Bus::batch($jobs)->then(function () use ($finalizeWeekStep) {
            FinalizeWeekJob::dispatch($finalizeWeekStep + 1);
        })->dispatch();
    }

    /**
     * @param int $groupSize
     * @return AttachWeeklySnapshotsToSideQuestResults
     */
    public function setGroupSize(int $groupSize): AttachWeeklySnapshotsToSideQuestResults
    {
        $this->groupSize = $groupSize;
        return $this;
    }

}
