<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Collections\AttackCollection;
use App\Domain\Models\Attack;
use App\Facades\AttackService;
use App\Jobs\BuildAttackSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;

class BuildCurrentWeekAttackSnapshotActions
{
    public function execute($step)
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekJob($step + 1)
        ]);
        $query = AttackService::query();
        $attacks = $query->get();
        $query->chunk(100, function (AttackCollection $attacks) use (&$chainGroup) {
            $jobs = $attacks->map(function (Attack $attack) {
                return new BuildAttackSnapshotJob($attack);
            });
            $chainGroup = $chainGroup->merge($jobs);
        });
        $chainGroup->dispatch();
    }
}
