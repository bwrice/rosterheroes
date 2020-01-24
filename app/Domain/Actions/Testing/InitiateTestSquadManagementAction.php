<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Models\Squad;
use App\Jobs\AutoManageSquadJob;

class InitiateTestSquadManagementAction
{
    public function execute()
    {
        Squad::query()->isTest()->get()->each(function (Squad $squad) {
            AutoManageSquadJob::dispatch($squad);
        });
    }
}
