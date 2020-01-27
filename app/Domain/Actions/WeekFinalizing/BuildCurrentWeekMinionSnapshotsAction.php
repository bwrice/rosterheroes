<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Minion;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepFiveJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Collection;

class BuildCurrentWeekMinionSnapshotsAction implements FinalizeWeekDomainAction
{
    public function execute(int $step)
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekJob($step + 1)
        ]);
        Minion::query()->chunk(100, function (Collection $minions) use ($chainGroup) {
            $jobs = $minions->map(function (Minion $minion) {
                return new BuildMinionSnapshotJob($minion);
            });
            $chainGroup->merge($jobs);
        });
        $chainGroup->dispatch();
    }
}
