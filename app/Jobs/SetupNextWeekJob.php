<?php

namespace App\Jobs;

use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Domain\Actions\SetupNextWeekAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class SetupNextWeekJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(SetupNextWeekAction $domainAction)
    {
        $domainAction->execute();
    }
}
