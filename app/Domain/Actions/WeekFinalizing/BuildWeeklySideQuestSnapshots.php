<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestSnapshot;
use App\Facades\CurrentWeek;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\BuildSideQuestSnapshotJob;
use App\Jobs\FinalizeWeekJob;
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

        Bus::batch($jobs->toArray())->then(function () use ($weekFinalizingStep) {
            FinalizeWeekJob::dispatch($weekFinalizingStep + 1);
        })->dispatch();
    }
}
