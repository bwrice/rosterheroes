<?php

namespace App\Jobs;

use App\Domain\Actions\Snapshots\BuildSideQuestSnapshot;
use App\Domain\Models\SideQuest;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSideQuestSnapshotJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SideQuest $sideQuest;

    public function __construct(SideQuest $sideQuest)
    {
        $this->sideQuest = $sideQuest;
    }

    /**
     * @param BuildSideQuestSnapshot $buildSideQuestSnapshot
     * @throws \Exception
     */
    public function handle(BuildSideQuestSnapshot $buildSideQuestSnapshot)
    {
        $buildSideQuestSnapshot->execute($this->sideQuest);
    }
}
