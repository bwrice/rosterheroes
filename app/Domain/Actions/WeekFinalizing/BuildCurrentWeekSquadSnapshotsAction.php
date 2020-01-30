<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Squad;
use App\Jobs\BuildSquadSnapshotJob;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepFourJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Collection;

class BuildCurrentWeekSquadSnapshotsAction implements FinalizeWeekDomainAction
{
    public function execute(int $finalizeWeekStep)
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekJob($finalizeWeekStep + 1)
        ]);
        Squad::query()->with(['heroes'])->chunk(100, function(Collection $squads) use ($chainGroup) {
            $squads->filter(function (Squad $squad) {
                return $squad->combatReady();
            })->each(function (Squad $squad) use ($chainGroup) {
                $chainGroup->push(new BuildSquadSnapshotJob($squad));
            });
        });
        $chainGroup->dispatch();
    }
}
