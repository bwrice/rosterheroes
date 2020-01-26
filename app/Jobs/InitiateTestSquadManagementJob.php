<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\InitiateTestSquadManagementAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InitiateTestSquadManagementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param InitiateTestSquadManagementAction $domainAction
     */
    public function handle(InitiateTestSquadManagementAction $domainAction)
    {
        $count = $domainAction->execute();

        Log::alert($count .  AutoManageSquadJob::class . ' jobs dispatched');
    }
}
