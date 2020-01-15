<?php

namespace App\Jobs;

use App\Domain\Actions\WeekFinalizing\FinalizeWeekStepOneAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWeekStepOneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function handle(FinalizeWeekStepOneAction $domainAction)
    {
        $domainAction->execute();
    }
}
