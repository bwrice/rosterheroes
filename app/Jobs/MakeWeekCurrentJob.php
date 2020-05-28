<?php

namespace App\Jobs;

use App\Domain\Actions\MakeWeekCurrent;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeWeekCurrentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Week
     */
    public $week;

    /**
     * MakeWeekCurrentJob constructor.
     * @param Week $week
     */
    public function __construct(Week $week)
    {
        $this->week = $week;
    }

    /**
     * @param MakeWeekCurrent $domainAction
     * @throws \Exception
     */
    public function handle(MakeWeekCurrent $domainAction)
    {
        $domainAction->execute($this->week);
    }
}
