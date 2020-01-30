<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Titan;
use App\Jobs\BuildTitanSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\SetupNextWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Collection;

class BuildCurrentWeekTitanSnapshotsAction implements FinalizeWeekDomainAction
{
    public function execute(int $finalizeWeekStep)
    {
        $buildSnapshotJobs = collect();
        Titan::query()->chunk(100, function (Collection $titans) use (&$buildSnapshotJobs) {
            $buildSnapshotJobs = $buildSnapshotJobs->merge($titans->map(function (Titan $titan) {
                return new BuildTitanSnapshotJob($titan);
            }));
        });
        ChainGroup::create($buildSnapshotJobs->toArray(), [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ])->dispatch();
    }
}
