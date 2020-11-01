<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Minion;
use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use App\Notifications\BatchCompleted;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Bus;

class BuildWeeklyMinionSnapshots implements FinalizeWeekDomainAction
{
    /**
     * @param int $weekFinalizingStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $weekFinalizingStep, array $extra = [])
    {
        $jobs = Minion::query()->whereDoesntHave('minionSnapshots', function (Builder $builder) {
            $builder->where('week_id', '=', CurrentWeek::id());
        })->get()->map(function (Minion $minion) {
            return new BuildMinionSnapshotJob($minion);
        });

        Bus::batch($jobs->toArray())->then(function (Batch $batch) use ($weekFinalizingStep) {
            FinalizeWeekJob::dispatch($weekFinalizingStep + 1);
            Admin::notify(new BatchCompleted($batch));
        })->name($this->getBatchName())->dispatch();
    }

    protected function getBatchName()
    {
        return "Build Minion Snapshots for Week: " . CurrentWeek::id();
    }
}
