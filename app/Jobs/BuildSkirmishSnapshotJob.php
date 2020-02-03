<?php

namespace App\Jobs;

use App\Domain\Actions\BuildSkirmishSnapshotAction;
use App\Domain\Models\SideQuest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSkirmishSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SideQuest
     */
    public $skirmish;

    /**
     * BuildSkirmishSnapshotJob constructor.
     * @param SideQuest $skirmish
     */
    public function __construct(SideQuest $skirmish)
    {
        $this->skirmish = $skirmish;
    }

    /**
     * @param BuildSkirmishSnapshotAction $domainAction
     */
    public function handle(BuildSkirmishSnapshotAction $domainAction)
    {
        $domainAction->execute($this->skirmish);
    }
}
