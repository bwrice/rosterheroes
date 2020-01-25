<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Collections\SquadCollection;
use App\Domain\Models\Squad;
use App\Jobs\AutoManageSquadJob;
use Illuminate\Support\Facades\Log;

class InitiateTestSquadManagementAction
{
    public function execute()
    {
        $count = 0;
        Squad::query()->isTest()->chunk(100, function(SquadCollection $squads) use (&$count) {
            $count += $squads->count();
            $squads->each(function (Squad $squad) {
                AutoManageSquadJob::dispatch($squad);
            });
        });

        return $count;
    }
}
