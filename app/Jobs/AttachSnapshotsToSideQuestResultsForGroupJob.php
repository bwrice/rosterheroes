<?php

namespace App\Jobs;

use App\Domain\Actions\AddAttachSnapshotToSideQuestResultJobsToBatch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachSnapshotsToSideQuestResultsForGroupJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $startRangeID;
    public int $endRangeID;

    public function __construct(int $startRangeID, int $endRangeID)
    {
        $this->startRangeID = $startRangeID;
        $this->endRangeID = $endRangeID;
    }


    public function handle(AddAttachSnapshotToSideQuestResultJobsToBatch $domainAction)
    {
        $domainAction->execute($this->batch(), $this->startRangeID, $this->endRangeID);
    }
}
