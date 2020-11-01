<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\SideQuest;
use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Jobs\BuildSideQuestSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use App\Notifications\BatchCompleted;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Bus;

class BuildWeeklySideQuestSnapshots implements FinalizeWeekDomainAction
{
    /**
     * @param int $weekFinalizingStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $weekFinalizingStep, array $extra = [])
    {
        $jobs = SideQuest::query()->whereDoesntHave('sideQuestSnapshots', function (Builder $builder) {
            $builder->where('week_id', '=', CurrentWeek::id());
        })->get()->map(function (SideQuest $sideQuest) {
            return new BuildSideQuestSnapshotJob($sideQuest);
        });

        Bus::batch($jobs)->then(function (Batch $batch) use ($weekFinalizingStep) {
            FinalizeWeekJob::dispatch($weekFinalizingStep + 1);
            Admin::notify(new BatchCompleted($batch));
        })->name($this->getBatchName())->dispatch();
    }

    protected function getBatchName()
    {
        return "Build Side Quest Snapshots For Week: " . CurrentWeek::id();
    }
}
