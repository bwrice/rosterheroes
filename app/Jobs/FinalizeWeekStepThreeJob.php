<?php

namespace App\Jobs;

use App\Domain\Actions\WeekFinalizing\FinalizeWeekStepThreeAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWeekStepThreeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param FinalizeWeekStepThreeAction $domainAction
     */
    public function handle(FinalizeWeekStepThreeAction $domainAction)
    {
        $domainAction->execute();
    }
}
