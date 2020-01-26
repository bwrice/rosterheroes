<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Squad;
use App\Jobs\BuildSquadSnapshotJob;
use App\Jobs\FinalizeWeekStepFourJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Collection;

class BuildCurrentWeekSquadSnapshotsAction
{
    public function execute()
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekStepFourJob()
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
