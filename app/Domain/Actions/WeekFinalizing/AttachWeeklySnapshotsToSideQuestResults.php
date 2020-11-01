<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\SideQuestResult;
use App\Facades\CurrentWeek;
use App\Jobs\AttachSnapshotsToSideQuestResultsForGroupJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AttachWeeklySnapshotsToSideQuestResults extends BatchedWeeklyAction
{
    protected int $groupSize = 200;
    protected string $name = 'Attach Snapshots to Side Quests';

    /**
     * @param int $groupSize
     * @return AttachWeeklySnapshotsToSideQuestResults
     */
    public function setGroupSize(int $groupSize): AttachWeeklySnapshotsToSideQuestResults
    {
        $this->groupSize = $groupSize;
        return $this;
    }

    protected function jobs(): \Illuminate\Support\Collection
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

        return $jobs;
    }

}
