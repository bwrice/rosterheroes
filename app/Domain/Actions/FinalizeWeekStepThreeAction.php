<?php


namespace App\Domain\Actions;


use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Jobs\BuildSquadSnapshotsJob;
use App\Jobs\FinalizeWeekStepFourJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Database\Eloquent\Collection;

class FinalizeWeekStepThreeAction
{
    public function execute()
    {
        $chainGroup = ChainGroup::create([], [
            new FinalizeWeekStepFourJob()
        ]);
        Squad::query()->chunk(100, function(Collection $squads) use ($chainGroup) {
            $squads->filter(function (Squad $squad) {
                return $squad->combatReady();
            })->each(function (Squad $squad) use ($chainGroup) {
                $chainGroup->push(new BuildSquadSnapshotsJob($squad));
            });
        });
        $chainGroup->dispatch();
    }
}
