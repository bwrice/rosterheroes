<?php

namespace App\Jobs;

use App\Domain\Actions\FinalizeWeekStepTwoAction;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeWeekStepTwoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FinalizeWeekStepTwoAction $domainAction)
    {
        $domainAction->execute();
    }
}
