<?php

namespace App\Jobs;

use App\Domain\Actions\WeekFinalizing\FinalizeWeekStepFiveAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWeekStepFiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(FinalizeWeekStepFiveAction $domainAction)
    {
        $domainAction->execute();
    }
}
