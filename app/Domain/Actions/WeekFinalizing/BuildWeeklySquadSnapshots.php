<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Squad;
use App\Jobs\BuildSquadSnapshotsForGroupJob;
use App\Jobs\FinalizeWeekJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;

class BuildWeeklySquadSnapshots implements FinalizeWeekDomainAction
{
    protected int $groupSize = 100;

    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        $jobs = collect();

        Squad::query()
            ->orderBy('id')
            ->select(['id'])
            ->chunk($this->groupSize, function (Collection $squads) use ($jobs) {
                $startRangeID = $squads->first()->id;
                $endRangeID = $squads->last()->id;
                $jobs->push(new BuildSquadSnapshotsForGroupJob($startRangeID, $endRangeID));
            });

        Bus::batch($jobs)->then(function () use ($finalizeWeekStep) {
            FinalizeWeekJob::dispatch($finalizeWeekStep + 1);
        })->dispatch();
    }

    /**
     * @param int $groupSize
     * @return BuildWeeklySquadSnapshots
     */
    public function setGroupSize(int $groupSize): BuildWeeklySquadSnapshots
    {
        $this->groupSize = $groupSize;
        return $this;
    }
}
