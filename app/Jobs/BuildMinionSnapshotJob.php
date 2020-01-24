<?php

namespace App\Jobs;

use App\Domain\Actions\BuildMinionSnapshotAction;
use App\Domain\Models\Minion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildMinionSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Minion
     */
    public $minion;

    public function __construct(Minion $minion)
    {
        $this->minion = $minion;
    }

    /**
     * @param BuildMinionSnapshotAction $domainAction
     */
    public function handle(BuildMinionSnapshotAction $domainAction)
    {
        $domainAction->execute($this->minion);
    }
}
