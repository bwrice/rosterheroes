<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Squad;
use App\Jobs\BuildSquadSnapshotsForGroupJob;
use Illuminate\Database\Eloquent\Collection;

class BuildWeeklySquadSnapshots extends BatchedWeeklyAction
{
    protected int $groupSize = 100;
    protected string $name = 'Build Squad Snapshots';

    protected function jobs(): \Illuminate\Support\Collection
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

        return $jobs;
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
