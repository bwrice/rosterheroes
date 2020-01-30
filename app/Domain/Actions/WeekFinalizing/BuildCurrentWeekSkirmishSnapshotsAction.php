<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\SkirmishCollection;
use App\Domain\Models\Attack;
use App\Domain\Models\Skirmish;
use App\Facades\AttackService;
use App\Facades\SkirmishService;
use App\Jobs\BuildAttackSnapshotJob;
use App\Jobs\BuildSkirmishSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;

class BuildCurrentWeekSkirmishSnapshotsAction implements FinalizeWeekDomainAction
{
    public function execute(int $finalizeWeekStep)
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ]);
        SkirmishService::query()->chunk(100, function (SkirmishCollection $skirmishes) use (&$chainGroup) {
            $jobs = $skirmishes->map(function (Skirmish $skirmish) {
                return new BuildSkirmishSnapshotJob($skirmish);
            });
            $chainGroup = $chainGroup->merge($jobs);
        });
        $chainGroup->dispatch();
    }
}
